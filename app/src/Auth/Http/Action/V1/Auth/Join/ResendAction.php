<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth\Join;

use App\Auth\Command\JoinByEmail\Resend\Command;
use App\Auth\Command\JoinByEmail\Resend\Handler;
use App\Auth\Form\JoinByEmail\ResendVerificationEmailForm;
use DomainException;
use Yii;
use yii\base\Action;
use yii\web\Response;

class ResendAction extends Action
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

        $form = new ResendVerificationEmailForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            $command = new Command();
            $command->email = $form->email ?? '';

            try {
                $this->handler->handle($command);

                Yii::$app->session->setFlash(
                    'success',
                    'Проверьте свою электронную почту для получения дальнейших инструкций.'
                );
                return $this->controller->goHome();

            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('resendVerificationEmail', [
            'model' => $form
        ]);
    }
}
