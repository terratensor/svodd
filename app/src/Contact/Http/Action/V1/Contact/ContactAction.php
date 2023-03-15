<?php

declare(strict_types=1);

namespace App\Contact\Http\Action\V1\Contact;

use App\Contact\Command\SendEmail\Request\Command;
use App\Contact\Form\SendEmail\ContactForm;
use App\Contact\Command\SendEmail\Request\Handler;
use Yii;
use yii\base\Action;
use yii\web\Response;

class ContactAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run(): Response|string
    {
        $model = new ContactForm(Yii::$app->user->identity);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $command = new Command();
            $command->name = $model->name ?? '';
            $command->email = $model->email ?? '';
            $command->subject = $model->subject ?? '';
            $command->body = $model->body ?? '';

            if ($this->handler->handle($command)) {
                Yii::$app->session->setFlash(
                    'success',
                    'Спасибо за ваше сообщение, обратную связь.'
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    'Произошла ошибка при оправке сообщения.'
                );
            }

            return $this->controller->refresh();
        }

        return $this->controller->render('contact', [
            'model' => $model,
        ]);
    }
}
