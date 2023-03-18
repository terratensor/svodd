<?php

namespace App\Contact\Service;

use App\Auth\Entity\User\Email;
use App\Contact\Command\SendEmail\Request\Command;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\Mime\Address;
use Twig\Environment;
use Yii;

class FeedbackSender
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Command $command): void
    {
        $email = new Email($command->email);

        $message = (new MimeEmail())
            ->subject($command->subject)
            ->to(Yii::$app->params['from']['email'])
            ->replyTo(new Address($email->getValue(), $command->name))
            ->html(                                                                                        $this->twig->render(
                'contact/feedback.html.twig', ['subject' => $command->subject, 'text' => $command->body]), 'UTF-8');

        $this->mailer->send($message);
    }
}
