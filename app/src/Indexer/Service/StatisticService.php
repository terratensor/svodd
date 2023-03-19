<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Question\Entity\Question\QuestionRepository;
use App\repositories\Question\QuestionStatsRepository;

class StatisticService
{
    private QuestionRepository $questionRepository;
    private QuestionStatsRepository $questionStatsRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionStatsRepository $questionStatsRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->questionStatsRepository = $questionStatsRepository;
    }

    public function update(string $question_id): void
    {
        $question = $this->questionRepository->get($question_id);
        $comments_count = count($question->comments);

        $comments = $question->comments;
        $lastComment = array_pop($comments);
        $lastCommentDate = $lastComment->datetime;

        $stats = $this->questionStatsRepository->getByQuestionId($question->data_id);

        if ($stats->questionDate === null) {
            $stats->questionDate = $question->datetime;
        }

        $stats->changeCommentsCount($comments_count, $lastCommentDate);

        $this->questionStatsRepository->save($stats);
    }
}
