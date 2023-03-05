<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth\Reset\Password;

use App\Auth\Command\ResetPassword\Confirm\Command;
use App\Auth\Command\ResetPassword\Confirm\Handler;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Form\ResetPassword\ResetPasswordForm;
use DomainException;
use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ConfirmAction extends Action
{
    private Handler $handler;
    private UserRepository $users;

    public function __construct(
        $id,
        $controller,
        Handler $handler,
        UserRepository $users,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
        $this->users = $users;
    }

    /**
     * @throws BadRequestHttpException
     */
    public function run(string $token): Response|string
    {
        if (!$this->users->findByPasswordResetToken($token)) {
            throw new BadRequestHttpException('Токен не найден.');
        }

        $form = new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            $command = new Command();
            $command->password = $form->password;
            $command->token = $token;

            try {
                $this->handler->handle($command);

                Yii::$app->session->setFlash('success', 'Сохранен новый пароль.');
                return $this->controller->goHome();

            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->render('resetPassword', [
            'model' => $form,
        ]);
    }
}
