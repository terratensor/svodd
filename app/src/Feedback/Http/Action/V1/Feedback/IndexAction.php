<?php

namespace App\Feedback\Http\Action\V1\Feedback;

use App\Feedback\Entity\Feedback\Feedback;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    public function run(): string
    {
        $query = Feedback::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
            ]
        );
        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
