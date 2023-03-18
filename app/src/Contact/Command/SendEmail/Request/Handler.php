<?php

declare(strict_types=1);

namespace App\Contact\Command\SendEmail\Request;

use App\Contact\Model\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;
use Yii;

class Handler
{
    private Mailer $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        $email = (new MimeEmail())
            ->subject($command->subject)
            ->to(Yii::$app->params['from']['email'])
            ->replyTo(new Address($email->getValue(), $command->name))
            ->html( $this->twig->render(
                'contact/feedback.html.twig',
                [
                    'subject' => $command->subject,
                    'text' => $command->body
                ]), 'text/html');

        $this->mailer->send($email);
    }
}
