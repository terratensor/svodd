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



class JoinConfirmationSender
{
    private MailerInterface $mailer;
    private FrontendUrlGenerator $frontend;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig, FrontendUrlGenerator $frontend)
    {
        $this->mailer = $mailer;
        $this->frontend = $frontend;
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
            ->subject('Подтвердите регистрацию')
            ->to($email->getValue())
            ->html($this->twig->render('auth/join/confirm.html.twig', ['token' => $token]), 'text/html');

        $this->mailer->send($message);
    }
}
