<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Confirm\Command;
use App\Auth\Command\JoinByEmail\Confirm\Handler;
use common\auth\Identity;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\Response;

class ConfirmAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run(string $token): Response
    {
         // if we have referer from session, we should redirect to it
        // after user login
        // and remove this referer from session
        $referer = \Yii::$app->session->get('bookmark_REFERER');

        $command = new Command();
        $command->token = $token ?? '';

        try {
            $user = $this->handler->handle($command);
            Yii::$app->session->setFlash('success', 'Ваш адрес электронной почты был подтвержден!');
            Yii::$app->user->login(new Identity($user));
            // if we have referer from session, we should redirect to it
            // after user login
            // and remove this referer from session
            if ($referer) {
                Yii::$app->session->remove('bookmark_REFERER');
                return $this->controller->redirect($referer);
            }
            return $this->controller->goHome();
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', 'Извините, мы не можем подтвердить вашу учетную запись с помощью предоставленного токена.');
        }
        return $this->controller->goHome();
    }
}
