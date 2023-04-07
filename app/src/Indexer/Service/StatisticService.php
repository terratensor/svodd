<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Question\Entity\Question\CommentReadModel;
use App\Question\Entity\Question\Question;
use App\Question\Entity\Question\QuestionRepository;
use App\Question\Entity\Statistic\QuestionStatsRepository;
use App\Svodd\Service\ChartDataUpdater;

class StatisticService
{
    private QuestionRepository $questionRepository;
    private QuestionStatsRepository $questionStatsRepository;
    private CommentReadModel $commentReadModel;
    private ChartDataUpdater $chartDataUpdater;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionStatsRepository $questionStatsRepository,
        CommentReadModel $commentReadModel,
        ChartDataUpdater $chartDataUpdater,
    ) {
        $this->questionRepository = $questionRepository;
        $this->questionStatsRepository = $questionStatsRepository;
        $this->commentReadModel = $commentReadModel;
        $this->chartDataUpdater = $chartDataUpdater;
    }

    /**
     * Обновление статистики по вопросам, проходим в цикле по всем вопросам и обновляем данные статистики
     */
    public function updateAll(): void
    {
        $questionIDs = Question::find()
            ->andWhere(['is not', 'data_id', null])
            ->select(['id'])
            ->asArray()
            ->all();

        foreach ($questionIDs as $key => $questionID) {
            $question = $this->questionRepository->get($questionID['id']);
            $this->update($question->id);
            echo "Update question $question->id \r\n";
        }
    }

    public function update(string $question_id): void
    {
        $question = $this->questionRepository->get($question_id);
        $comments_count = $this->commentReadModel->commentsCountByQuestion($question->data_id);

        echo "commentsCountByQuestion $comments_count\r\n";

        // Получение номера data_id последнего комментария в вопросе
        $lastCommentDataId = $this->commentReadModel->findMaxDataIdByQuestion($question->data_id);
        echo "lastCommentDataId $lastCommentDataId\r\n";

        $lastComment = $lastCommentDataId ? $this->commentReadModel->findByDataId($lastCommentDataId) : null;
        // Получение даты и времени последнего комментария вопроса
        $lastCommentDate = $lastComment->datetime ?? null;
        echo "load lastCommentDate \r\n";

        // Получение номера первого комментария вопроса (первый элемент массива)
        $firstCommentDataId = $this->commentReadModel->findMinDataIdByQuestion($question->data_id);
        echo "firstCommentDataId $firstCommentDataId\r\n";

        $stats = $this->questionStatsRepository->getByQuestionId($question->data_id);
        echo "getByQuestionId stats\r\n";

        if ($stats->questionDate === null) {
            $stats->questionDate = $question->datetime;
        }

        $stats->changeCommentsCount($comments_count, $lastCommentDate);
        $stats->changeLastCommentDataId($lastCommentDataId);
        $stats->changeFirstCommentDataId($firstCommentDataId);

        $this->questionStatsRepository->save($stats);
        echo "questionStatsRepository save\r\n";

        // Обновляем запись диаграммы статистики, тут должен быть listener
        $this->chartDataUpdater->handle($question->data_id);
    }
}
