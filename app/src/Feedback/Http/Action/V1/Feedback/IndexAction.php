<?php

namespace App\Feedback\Http\Action\V1\Feedback;

use App\Feedback\Command\SendMessage\Command;
use App\Feedback\Command\SendMessage\Handler;
use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Form\SendMessage\FeedbackForm;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;

class IndexAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run(): \yii\web\Response|string
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
        $form = new FeedbackForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            $command = new Command();
            $command->user_id = Yii::$app->user->getId();
            $command->text = $form->text;

            try {
                $feedback = $this->handler->handle($command);
                return $this->controller->redirect(['index', '#' => 'comment-' . $feedback->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $form,
        ]);
    }
}
