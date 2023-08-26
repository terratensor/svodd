<?php

declare(strict_types=1);

namespace App\Svodd\Entity\listeners;

use App\Question\Entity\Question\CommentReadModel;
use App\Svodd\Entity\Chart\events\StartCommentDataIDSetter;
use App\Svodd\Entity\Chart\SvoddChartRepository;

class CommentDataIDSetterListener
{
    private SvoddChartRepository $repository;
    private CommentReadModel $commentReadModel;

    public function __construct(SvoddChartRepository $repository, CommentReadModel $commentReadModel)
    {
        $this->repository = $repository;
        $this->commentReadModel = $commentReadModel;
    }

    public function handle(StartCommentDataIDSetter $event): void
    {
        $question_id = $event->question_id;
        $current = $this->repository->findCurrent();
        $result = $this->commentReadModel->findByQuestionToday($question_id);

        try {
            if ($result && key_exists('data_id', $result)) {
                $current->start_comment_data_id = $result['data_id'];
                $this->repository->save($current);
            } else {
                throw new \DomainException('не удалось установить начальный комментарий темы вопроса');
            }
        } catch (\Exception $e) {
            \Sentry\captureException($e);
        }
    }
}
