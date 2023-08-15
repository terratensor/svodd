<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Svodd\Entity\Chart\Data;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use Yii;
use yii\base\InvalidConfigException;

class QuestionListHandler
{
    private SvoddChartRepository $repository;

    public string $url_prefix = "https://фкт-алтай.рф/qa/question/view-";
    private ChartDatetimeSetter $datetimeSetter;

    public function __construct(SvoddChartRepository $repository, ChartDatetimeSetter $datetimeSetter) {
        $this->repository = $repository;
        $this->datetimeSetter = $datetimeSetter;
    }

    public function handle(): array
    {
        $models = $this->repository->findAllAsc();
        $response = [];
        foreach ($models as $model) {
            /** @var $model Data */
            if ($model->start_datetime === null) {
                $this->datetimeSetter->handle();
            }
            try {
                $date = Yii::$app->formatter->asDatetime($model->start_datetime, 'php:d.m.y');
            } catch (InvalidConfigException $e) {
                $date = '';
            }
            $response['list'][] = [
                'id' => $model->question_id,
                'num' => str_pad((string)$model->topic_number, 2, '0', STR_PAD_LEFT),
                'date' =>  $date,
                'url' => $this->url_prefix . $model->question_id,
                'start_comment' => $model->start_comment_data_id,
                'end_comment' => $model->end_comment_data_id,
                'comments' => $model->comments_count,
                'delta' => $model->comments_delta,
            ];
        }
        return $response;
    }
}
