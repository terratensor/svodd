<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;
use RuntimeException;
use yii\db\ActiveRecord;

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
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    public function findByJoinConfirmToken(string $token): array|ActiveRecord|null|User
    {
        return User::find()->andWhere(['join_confirm_token_value' => $token])->one();
    }

    /**
     * @param Id $id
     * @return array|ActiveRecord|User
     */
    public function get(Id $id): array|ActiveRecord|User
    {
        if (!$user = User::find()->andWhere(['id' => $id->getValue()])->one()) {
            throw new DomainException('Пользователь не найден.');
        }
        return $user;
    }

    /**
     * @param Email $email
     * @return ActiveRecord|array|User
     */
    public function getByEmail(Email $email): User|array|ActiveRecord
    {
        if (!$user = User::find()->andWhere(['email' => $email->getValue()])->one()) {
            throw new DomainException('user id not found.');
        }
        return $user;
    }

    /**
     * @param string $token
     * @return User|null|ActiveRecord
     */
    public function findByPasswordResetToken(string $token): User|ActiveRecord|null
    {
        return User::find()->andWhere(['password_reset_token_value' => $token])->one();
    }
}
