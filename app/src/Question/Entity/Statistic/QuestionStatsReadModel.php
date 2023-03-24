<?php

namespace App\Question\Entity\Statistic;

use Yii;
use yii\data\ActiveDataProvider;

class QuestionStatsReadModel
{
    public function findAll(): ActiveDataProvider
    {
        $query = QuestionStats::find()
            ->andWhere(['IS NOT', 'number', null])
            ->orderBy("number ASC");

        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => ['date'],
                    'defaultOrder' => ['date' => SORT_ASC],
                ]
            ]
        );
    }
}
