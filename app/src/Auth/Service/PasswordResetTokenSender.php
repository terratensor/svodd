<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Yii;


class PasswordResetTokenSender
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(Email $email, Token $token): void
    {
        $message = (new MimeEmail())
            ->subject('Сброс пароля')
            ->to($email->getValue())
            ->html($this->twig->render('auth/password/confirm.html.twig', ['token' => $token]), 'text/html');

        $this->mailer->send($message);
    }
}
