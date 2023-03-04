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
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    'There was an error sending your message.'
                );
            }

            return $this->controller->refresh();
        }

        return $this->controller->render('contact', [
            'model' => $model,
        ]);
    }
}
