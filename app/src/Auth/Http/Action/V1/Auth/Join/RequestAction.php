<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Request\Command;
use App\Auth\Command\JoinByEmail\Request\Handler;
use App\Auth\Form\JoinByEmail\RequestForm;
use DomainException;
use Yii;
use yii\base\Action;
use yii\web\Response;

class RequestAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run(): Response|string
    {
        // if we have referer from session, we should redirect to it
        // after user login
        // and remove this referer from session
        $referer = \Yii::$app->session->get('bookmark_REFERER');

        $form = new RequestForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $command = new Command();
                $command->email = $form->email;
                $command->password = $form->password;

                $this->handler->handle($command);
                Yii::$app->session->setFlash('success', 'Благодарим вас за регистрацию. Пожалуйста, проверьте свой почтовый ящик на наличие подтверждающего электронного письма.');
                // if we have referer from session, we should redirect to it
                // after user login
                // and remove this referer from session
                if ($referer) {
                    Yii::$app->session->remove('bookmark_REFERER');
                    return $this->controller->redirect($referer);
                }
                return $this->controller->goHome();
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this
            ->controller
            ->render('request', ['model' => $form]);
    }
}
