<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Command\UpdateTopic\Request;

use App\Question\Entity\Question\CommentReadModel;
use App\Svodd\Service\ChartDatetimeSetter;
use App\Svodd\Service\SvoddService;
use function PHPUnit\Framework\throwException;

class Handler
{
    private SvoddService $service;
    protected ChartDatetimeSetter $datetimeSetter;
    private CommentReadModel $comments;

    public function __construct(SvoddService $service, ChartDatetimeSetter $datetimeSetter, CommentReadModel $comments)
    {
        $this->service = $service;
        $this->datetimeSetter = $datetimeSetter;
        $this->comments = $comments;
    }

    public function handle(Command $command): void
    {
        $this->service->changeCurrent($command->url, $command->number, $command->data_id);
//        $this->comments->
        $this->datetimeSetter->handle();
        $this->service->updateStatistic();
    }
}
