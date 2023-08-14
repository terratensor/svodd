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

    public function __construct(SvoddChartRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(): array
    {
        $models = $this->repository->findAllAsc();
        $response = [];
        foreach ($models as $model) {
            /** @var $model Data */
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
                'comments' => $model->comments_count,
            ];
        }
        return $response;
    }
}
