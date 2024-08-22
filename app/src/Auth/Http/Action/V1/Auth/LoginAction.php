<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth;

use App\Auth\Command\Login\Command;
use App\Auth\Command\Login\Handler;
use App\Auth\Form\Login\LoginForm;
use common\auth\Identity;
use Exception;
use Yii;
use yii\base\Action;
use yii\web\Response;

class LoginAction extends Action
{
    private Handler $handler;

    public function __construct($id, $controller, Handler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        // if we have referer from session, we should redirect to it
        // after user login
        // and remove this referer from session
        $referer = \Yii::$app->session->get('bookmark_REFERER');

        // get current referer
        $ref = Yii::$app->request->getReferrer();

        // if we have referer from session and it is not equal to current referer
        // we should remove this referer from session
        if ($referer && $ref && strpos($referer, $ref) === false) {
            \Yii::$app->session->remove('bookmark_REFERER');
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $command = new Command();
                $command->email = $form->email ?? '';
                $command->password = $form->password ?? '';
                $user = $this->handler->auth($command);

                Yii::$app
                    ->user
                    ->login(new Identity($user), $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                
                    
                    // if we have referer from session, we should redirect to it
                    // after user login
                    // and remove this referer from session
                    if ($referer) {
                        Yii::$app->session->remove('bookmark_REFERER');
                        return $this->controller->redirect($referer);
                    }

                return $this->controller->goBack();
            } catch (Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('login', [
            'model' => $form,
        ]);
    }
}
