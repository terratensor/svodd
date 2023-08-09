<?php

declare(strict_types=1);

namespace App\Question\Entity\listeners;

use App\Question\Entity\Question\events\CommentCreated;

class CommentCreatedListener
{
    public function handle(CommentCreated $event): void
    {
        $text = $event->text;
        echo $text;
    }
}
