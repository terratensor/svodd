<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth\Reset\Password;

use App\Auth\Command\ResetPassword\Request\Command;
use App\Auth\Command\ResetPassword\Request\Handler;
use App\Auth\Form\ResetPassword\PasswordResetRequestForm;
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
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $command = new Command();
            $command->email = $model->email;

            try {
                if ($this->handler->handle($command)) {
                    Yii::$app->session->setFlash(
                        'success',
                        'Проверьте свою электронную почту для получения дальнейших инструкций.'
                    );

                    return $this->controller->goHome();
                }
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
}
