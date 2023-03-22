<?php

namespace App\User\Profile\Entity;

use Yii;

class ProfileRepository
{
    public function get(Id $id): Profile
    {
        if (!$profile = Profile::findOne($id)) {
            throw new \DomainException('Профиль пользователя не найден.');
        }
        return $profile;
    }

    public function save(Profile $profile): void
    {
        if (!$profile->save()) {
            throw new \RuntimeException('Ошибка при сохранении.');
        }
    }

    public function delete(Profile $profile): void
    {
        if (!$profile->delete()) {
            throw new \RuntimeException('Ошибка при удалении записи.');
        }
    }
}
