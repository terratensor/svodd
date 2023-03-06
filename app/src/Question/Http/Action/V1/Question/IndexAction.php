<?php

declare(strict_types=1);

namespace App\Question\Http\Action\V1\Question;

use App\models\QuestionStats;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    public function run(): string
    {
        $query = QuestionStats::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'attributes' => ['comments_count', 'question_date'],
                    'defaultOrder' => ['comments_count' => SORT_DESC],
                ]
            ]
        );
        return $this->controller->render('index',
        [
            'dataProvider' => $dataProvider,
        ]);
    }
}
