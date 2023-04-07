<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Statistic\QuestionStatsRepository;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use Yii;

class ChartDataUpdater
{
    private QuestionStatsRepository $questionStatsRepository;
    private SvoddChartRepository $svoddChartRepository;

    public function __construct(
        QuestionStatsRepository $questionStatsRepository,
        SvoddChartRepository $svoddChartRepository,
    ) {
        $this->questionStatsRepository = $questionStatsRepository;
        $this->svoddChartRepository = $svoddChartRepository;
    }

    public function handle(int $question_id): void
    {
        $data = $this->svoddChartRepository->findByQuestionId($question_id);
        echo "char data updater load data\r\n";

        try {
            $stats = $this->questionStatsRepository->getByQuestionId($question_id);
            echo "char data updater load stats\r\n";
            // если вопрос активный, то обновляем дату последнего комментария,
            // иначе обновятся предыдущие записи последнего комментария и подсчет для диаграммы поломается
            // эта дата должна быть зафиксирована после смены активной темы,
            // пока тема активна обновляется, после перехода в статус неактивна зафиксирована.
            if ($data->isActive()) {
                $data->end_comment_data_id = $stats->last_comment_data_id;
            }
            // обновляем количество комментариев в теме
            $data->comments_count = $stats->comments_count;

            $this->svoddChartRepository->save($data);
            echo "Сохранены данные диаграммы $question_id\r\n";
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
    }
}
