<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Symfony\Component\Mime\Email as MimeEmail;
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
        $send = $this->mailer->compose(
            ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
            ['url' => $this->frontend->generate('join/confirm', ['token' => $token->getValue()]),]
        )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($email->getValue())
            ->setSubject('Join Confirmation at ' . Yii::$app->name)
            ->send();

        var_dump($send); die();
    }
}
