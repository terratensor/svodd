<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Symfony\Component\Mime\Email as MimeEmail;
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
        $message = (new MimeEmail())
            ->subject('Join Confirmation')
            ->to($email->getValue())
            ->html($this->frontend->generate('join/confirm', ['token' => $token->getValue()]), 'text/html');

        $this->mailer->send($message);
    }
}
