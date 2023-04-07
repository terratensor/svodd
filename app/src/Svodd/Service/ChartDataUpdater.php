<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Question\CommentReadModel;
use App\Question\Entity\Statistic\QuestionStatsRepository;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use Yii;
use yii\helpers\ArrayHelper;

class ChartDataUpdater
{
    private QuestionStatsRepository $questionStatsRepository;
    private SvoddChartRepository $svoddChartRepository;
    private CommentReadModel $commentReadModel;

    public function __construct(
        QuestionStatsRepository $questionStatsRepository,
        SvoddChartRepository $svoddChartRepository,
        CommentReadModel $commentReadModel,
    ) {
        $this->questionStatsRepository = $questionStatsRepository;
        $this->svoddChartRepository = $svoddChartRepository;
        $this->commentReadModel = $commentReadModel;
    }

    public function handle(int $question_id): void
    {
        $data = $this->svoddChartRepository->findByQuestionId($question_id);

        $previous_data = $this->svoddChartRepository->findPreviousData($data);
        $previous_question_id = $previous_data?->question_id;

        if ($data !== null) {
            try {
                $stats = $this->questionStatsRepository->getByQuestionId($question_id);
                // если вопрос активный, то обновляем дату последнего комментария,
                // иначе обновятся предыдущие записи последнего комментария и подсчет для диаграммы поломается
                // эта дата должна быть зафиксирована после смены активной темы,
                // пока тема активна обновляется, после перехода в статус неактивна зафиксирована.
                if ($data->isActive()) {
                    $data->end_comment_data_id = $stats->last_comment_data_id;
                }
                // обновляем количество комментариев в теме
                $data->comments_count = $stats->comments_count;

                // получаем колонку с комментариями от предыдущего вопроса,
                // преобразовываем в массив ключ значение data_id комментария
                // и подсчитываем полученное кол-во элементов в массиве.
                $delta = count(ArrayHelper::getColumn(
                    $this->commentReadModel->findCommentIDsByQuestionAfter(
                        $previous_question_id,
                        $previous_data->end_comment_data_id
                    ), 'data_id'));

                // обновляем значение дельты, это кол-во комментариев в предыдущем вопросе,
                // после закрывающего комментария
                $data->comments_delta = $delta;

                $this->svoddChartRepository->save($data);
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
            }
        }
    }
}
