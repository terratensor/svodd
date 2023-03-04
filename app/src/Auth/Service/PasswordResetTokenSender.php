<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetTokenSender
{
    private MailerInterface $mailer;
    private FrontendUrlGenerator $frontend;

    public function __construct(MailerInterface $mailer, FrontendUrlGenerator $frontend)
    {
        $this->mailer = $mailer;
        $this->frontend = $frontend;
    }

    public function send(Email $email, Token $token): bool
    {
        return Yii::$app->mailer->compose(
            ['html' => '@common/mail/passwordResetToken-html', 'text' => '@common/mail/passwordResetToken-text'],
            ['url' => $this->frontend->generate('auth/reset/password-confirm', ['token' => $token->getValue()]),]
        )
            ->setFrom([Yii::$app->params['from']['email'] => Yii::$app->params['from']['name']])
            ->setTo($email->getValue())
            ->setSubject('Сброс пароля на ' . Yii::$app->name)
            ->send();
    }
}
