<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Yii;
use yii\mail\MailerInterface;
use yii\symfonymailer\Mailer;

class JoinConfirmationSender
{
    private MailerInterface $mailer;
    private FrontendUrlGenerator $frontend;

    public function __construct(Mailer $mailer, FrontendUrlGenerator $frontend)
    {
        $this->mailer = $mailer;
        $this->frontend = $frontend;
    }

    public function send(Email $email, Token $token): void
    {
        $send = Yii::$app->mailer->compose(
            ['html' => '@common/mail/emailVerify-html', 'text' => '@common/mail/emailVerify-text'],
            ['url' => $this->frontend->generate('join/confirm', ['token' => $token->getValue()]),]
        )
            ->setFrom([Yii::$app->params['from']['email'] => Yii::$app->params['from']['name']])
            ->setTo($email->getValue())
            ->setSubject('Подтвердите регистрацию на ' . Yii::$app->name)
            ->send();
    }
}
