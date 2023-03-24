<?php

namespace App\Question\Entity\Question;

use App\Question\Entity\Statistic\QuestionStats;
use Yii;
use yii\data\ActiveDataProvider;

class CommentReadModel
{
    public function findBySvoddQuestions(): ActiveDataProvider
    {
        $duration = 60;

        $questionIds = QuestionStats::getDb()->cache(function ($db) {
            return QuestionStats::find()
                ->alias('qs')
                ->select('question_id')
                ->andWhere(['not', ['qs.number' => null]])
                ->orderBy('qs.number')
                ->asArray()
                ->column();
        }, $duration);

        $query = Comment::find()
            ->alias('c')
            ->joinWith('questionStat qs')
            ->andWhere(['in', 'c.question_data_id', $questionIds])
            ;

        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'date' => [
                            'asc' => ['qs.number' => SORT_ASC, 'c.date' => SORT_ASC],
                            'desc' => ['qs.number' => SORT_DESC, 'c.date' => SORT_DESC],
                            'default' => ['qs.number' => SORT_ASC, 'c.date' => SORT_ASC],
                        ],
                    ],
                    'defaultOrder' => [
                        'date' => SORT_ASC
                    ],
                ]
            ]
        );
    }
}
