<?php

declare(strict_types=1);

namespace App\Contact\Command\SendEmail\Request;

use App\Contact\Model\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;
use Yii;

class Handler
{
    private Mailer $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        $email = (new MimeEmail())
            ->from(Yii::$app->params['from']['email'])
            ->to(Yii::$app->params['from']['email'])
            ->replyTo(new Address($email->getValue(), $command->name))
            ->subject($command->subject)
            ->text($command->body);

        $this->mailer->send($email);
    }
}
