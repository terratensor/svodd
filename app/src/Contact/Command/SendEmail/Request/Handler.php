<?php

declare(strict_types=1);

namespace App\Contact\Command\SendEmail\Request;

use App\Contact\Model\Email;
use Yii;

class Handler
{
    public function handle(Command $command): bool
    {
        $email = new Email($command->email);

        return Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([Yii::$app->params['from']['email'] => Yii::$app->params['from']['name']])
            ->setReplyTo([$email->getValue() => $command->name])
            ->setSubject($command->subject)
            ->setTextBody($command->body)
            ->send();
    }
}
