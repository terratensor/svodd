<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Command\UpdateTopic\Request;

use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\CommentRepository;
use App\Svodd\Service\ChartDatetimeSetter;
use App\Svodd\Service\SvoddService;

class Handler
{
    private SvoddService $service;
    protected ChartDatetimeSetter $datetimeSetter;
    private CommentRepository $commentRepository;

    public function __construct(SvoddService $service, ChartDatetimeSetter $datetimeSetter, CommentRepository $commentRepository)
    {
        $this->service = $service;
        $this->datetimeSetter = $datetimeSetter;
        $this->commentRepository = $commentRepository;
    }

    public function handle(Command $command): void
    {
        $newCurrent = $this->service->changeCurrent($command->url, $command->number, $command->data_id);
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
        $this->datetimeSetter->handle();
        $this->service->updateStatistic();
    }
}
