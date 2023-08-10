<?php

declare(strict_types=1);

namespace App\Question\Entity\listeners;

use App\Question\Entity\Question\events\CommentCreated;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class CommentCreatedListener
{
    private SvoddChartRepository $repository;

    public function __construct(SvoddChartRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function handle(CommentCreated $event): void
    {
        $current = $this->repository->findCurrent();

        if ($current->question_id !== $event->question_data_id) {
            return;
        }

        $exchange = getenv('RABBIT_EXCHANGE_NAME');
        $queue = getenv('RABBIT_QUEUE_NAME');

        $connection = new AMQPStreamConnection(
            getenv('RABBIT_HOSTNAME'),
            5672,
            getenv('RABBIT_USERNAME'),
            getenv('RABBIT_PASSWORD'),
            '/'
        );
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        $message = new AMQPMessage($event->text, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $exchange);

        $channel->close();
        $connection->close();
    }
}
