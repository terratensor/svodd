<?php

declare(strict_types=1);

namespace App\Question\Http\Action\V1\Question;

use App\models\QuestionStats;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class IndexAction extends Action
{
    public function run(): string
    {
        $query = QuestionStats::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => [
                        'comments_count',
                        'question_date' => [
                            'asc' => [new Expression('question_date NULLS LAST')],
                            'desc' => [new Expression('question_date DESC NULLS LAST')],
                        ],
                        'last_comment_date' => [
                            'asc' => [new Expression('last_comment_date NULLS LAST')],
                            'desc' => [new Expression('last_comment_date DESC NULLS LAST')],
                        ],
                    ],
                    'defaultOrder' => ['question_date' => SORT_DESC],
                ]
            ]
        );
        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
