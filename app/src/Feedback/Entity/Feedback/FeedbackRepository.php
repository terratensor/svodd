<?php

namespace App\Feedback\Entity\Feedback;

use RuntimeException;

class FeedbackRepository
{
    public function get(Id $id): Feedback
    {
        if (!$feedback = Feedback::findOne($id)) {
            throw new \DomainException('Сообщение не найдено.');
        }
        return $feedback;
    }

    public function save(Feedback $feedback): void
    {
        if (!$feedback->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    public function delete(Feedback $feedback): void
    {
        if (!$feedback->delete()) {
            throw new \RuntimeException('Ошибка при удалении записи.');
        }
    }
}
