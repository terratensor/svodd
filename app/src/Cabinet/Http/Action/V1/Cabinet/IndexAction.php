<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Action\V1\Cabinet;

use App\Cabinet\Form\UpdateTopic\UpdateTopicForm;
use App\Cabinet\Http\Command\UpdateTopic\Request\Command;
use App\Cabinet\Http\Command\UpdateTopic\Request\Handler;
use DomainException;
use Yii;
use yii\base\Action;

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
        $form = new UpdateTopicForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $command = new Command();
                $command->url = $form->url;

                $this->handler->handle($command);
                Yii::$app->session->setFlash('success', "Новая активная тема $command->number успешно активирована.");
                return $this->controller->goHome();
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('index', ['model' => $form]);
    }
}
