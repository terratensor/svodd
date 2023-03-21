<?php

namespace App\Feedback\Entity\Feedback;

use RuntimeException;

class FeedbackRepository
{
    public function save(Feedback $feedback): void
    {
        if (!$feedback->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }
}
