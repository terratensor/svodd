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

    /**
     * @param Email $email
     * @return array|ActiveRecord|User|null
     */
    public function findByEmail(Email $email): array|ActiveRecord|null|User
    {
        return User::find()
                ->andWhere(['email' => $email->getValue()])
                ->one();
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RuntimeException('Saving error.');
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
            throw new DomainException('User is not found.');
        }
        return $user;
    }
}
