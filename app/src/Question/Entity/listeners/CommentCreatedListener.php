<?php

declare(strict_types=1);

namespace App\Question\Entity\listeners;

use App\Question\Entity\Question\CommentRepository;
use App\Question\Entity\Question\events\CommentCreated;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use yii\helpers\Html;

class CommentCreatedListener
{
    const WAIT_BEFORE_RECONNECT_uS = 1000000;
    private SvoddChartRepository $repository;
    private CommentRepository $commentRepository;

    public function __construct(SvoddChartRepository $repository, CommentRepository $commentRepository)
    {
        $this->repository = $repository;
        $this->commentRepository = $commentRepository;
    }

    public function handle(CommentCreated $event): void
    {
        if ((getenv('APP_ENV') === 'prod')) {
            \Sentry\init(['dsn' => trim(file_get_contents(getenv('SENTRY_DSN_FILE')))]);
        }

        $current = $this->repository->findCurrent();

        if ($current->question_id !== $event->question_data_id) {
            return;
        }

        $link = $this->createCommentLink($event);

        $connection = null;
        $channel = null;

        $result = false;
        $attempts = 1;
        while ($attempts <= 30 && $result !== true) {
            try {
                $connection = new AMQPStreamConnection(
                    getenv('RABBIT_HOSTNAME'),
                    5672,
                    getenv('RABBIT_USERNAME'),
                    trim(file_get_contents(getenv('RABBIT_PASSWORD_FILE'))),
                    '/'
                );
                // Your application code goes here.
                $result = $this->publishMessage($connection, $event, $link);
                $connection->close();
            } catch (\Exception $e) {
                \Sentry\captureException($e);
                $this->cleanupConnection($connection);
                echo "Попытка $attempts, ожидаем для обновления соединения с RMQ\n";
                $attempts = $attempts + 1;
                usleep(self::WAIT_BEFORE_RECONNECT_uS);
            }
        }
    }

    public function cleanupConnection($connection): void
    {
        // Connection might already be closed.
        // Ignoring exceptions.
        try {
            $connection?->close();
        } catch (\Exception $e) {
        }
    }

    /**
     * @param AbstractConnection $connection
     * @throws \Exception
     */
    public function shutdown(AbstractConnection $connection): void
    {
        $connection->close();
    }

    private function publishMessage(AMQPStreamConnection $connection, CommentCreated $event, string $link): bool
    {
        $exchange = getenv('RABBIT_EXCHANGE_NAME');
        $queue = getenv('RABBIT_QUEUE_NAME');

        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        /**
         * Добавляем в заголовок сообщения ссылку на комментарий
         */
        $headers = new AMQPTable(
            [
                'comment_link' => html_entity_decode($link)
            ]
        );

        $message = new AMQPMessage(
            $event->text,
            [
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        // Устанавливаем headers для сообщения
        $message->set('application_headers', $headers);

        $channel->basic_publish($message, $exchange);

        $channel->close();

        return true;
    }

    private function createCommentLink(CommentCreated $event): string
    {
        $comment = $this->commentRepository->getByDataId($event->data_id);
        $link = "https://фкт-алтай.рф/qa/question/view-" . $event->question_data_id;
        return "★&nbsp;" . Html::tag('i', Html::a(
                'Источник',
                $link . "#:~:text=" . $comment->datetime->format('H:i d.m.Y'),
            ));
    }
}
