<?php

namespace App\Question\Entity\Question;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class CommentReadModel
{
    public function findBySvoddQuestions(): ActiveDataProvider
    {
        $subQuery = (new Query())
            ->select(['question_id'])
            ->from('question_stats')
            ->where(['not', ['number' => null]])
        ;

//        var_dump($subQuery);

        $query = Comment::find()
            ->andWhere(['in', 'question_data_id', $subQuery])
            ;

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
