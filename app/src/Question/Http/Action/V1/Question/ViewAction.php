<?php

declare(strict_types=1);

namespace App\Question\Http\Action\V1\Question;

use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\Question;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{
    /**
     * @throws NotFoundHttpException
     */
    public function run(string $id): string
    {
        if (($question = Question::find()->andWhere(['data_id' => $id])->one()) === null) {
            throw new NotFoundHttpException('Страница не найдена.');
        }
        $query = Comment::find()->andWhere(['question_data_id' => $id]);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => Yii::$app->params['questions']['pageSize'],
                ],
                'sort' => [
                    'attributes' => ['date', 'position'],
                    'defaultOrder' => ['position' => SORT_ASC],
                ]
            ]
        );

        return $this->controller->render(
            'view',
            [
                'dataProvider' => $dataProvider,
                'question' => $question
            ]);
    }
}
