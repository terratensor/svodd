<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use RuntimeException;

class UserRepository
{
    public function hasByEmail(Email $email): bool
    {
        return User::find()
            ->andWhere(['email' => $email->getValue()])
            ->scalar() > 0;
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RuntimeException('Saving error.');
        }
    }
}
