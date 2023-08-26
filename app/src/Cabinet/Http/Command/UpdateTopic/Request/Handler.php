<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Command\UpdateTopic\Request;

use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\CommentRepository;
use App\Svodd\Service\ChartDatetimeSetter;
use App\Svodd\Service\ChartTopicNumberGenerator;
use App\Svodd\Service\SvoddService;

class Handler
{
    private SvoddService $service;
    protected ChartDatetimeSetter $datetimeSetter;
    private CommentRepository $commentRepository;
    private ChartTopicNumberGenerator $generator;


    public function __construct(
        SvoddService $service,
        ChartDatetimeSetter $datetimeSetter,
        CommentRepository $commentRepository,
       ChartTopicNumberGenerator $generator
    )
    {
        $this->service = $service;
        $this->datetimeSetter = $datetimeSetter;
        $this->commentRepository = $commentRepository;

        $this->generator = $generator;
    }

    public function handle(Command $command): void
    {
        $number = $this->generator->generate();
        $command->number = (string)$number;

        $newCurrent = $this->service->changeCurrent($command->url, $command->number, $command->data_id);

        if ($newCurrent->start_comment_data_id) {
            $comments = $this->commentRepository->findCommentsByChartData($newCurrent);
            /**
             * Итерируемся по массиву полученных комментариев вызываем метод sendToQueue()
             * для записи события events\CommentCreated
             * и сохраняем их без изменения для запуска listener
             * @var $comment Comment
             */
            foreach ($comments as $comment) {
                $comment->sendToQueue();
                $this->commentRepository->save($comment);
            }
        }
        $this->datetimeSetter->handle();
        $this->service->updateStatistic();
    }
}
