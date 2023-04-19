<?php

declare(strict_types=1);

namespace App\Question\Http\Action\V1\Question;

use App\FeatureToggle\FeatureFlag;
use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\Question;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{
    private FeatureFlag $flag;

    public function __construct($id, $controller, FeatureFlag $flag, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->flag = $flag;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function run(string $id, $feature = null): string
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
                    'attributes' => ['date'],
                    'defaultOrder' => ['date' => SORT_ASC],
                ]
            ]
        );

        foreach ($this->flag->features as $key => $value) {
            if ($feature === $key) {
                $this->flag->enable($key);
            }
        }

        if ($this->flag->isEnabled('SEARCH_FIX_DATE')) {
            return $this->controller->render('feature/view', [
                'dataProvider' => $dataProvider,
                'question' => $question
            ]);
        }

        return $this->controller->render(
            'view',
            [
                'dataProvider' => $dataProvider,
                'question' => $question
            ]);
    }
}
