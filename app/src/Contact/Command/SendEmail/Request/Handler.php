<?php

declare(strict_types=1);

namespace App\Contact\Command\SendEmail\Request;

use App\Contact\Service\FeedbackSender;

class Handler
{
    private FeedbackSender $sender;

    public function __construct(FeedbackSender $sender)
    {
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $this->sender->send($command);
    }
}
