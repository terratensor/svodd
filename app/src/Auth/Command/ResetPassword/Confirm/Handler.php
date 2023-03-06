<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Confirm;

use App\Auth\Entity\User\AuthKey;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;

    public function __construct(UserRepository $users, PasswordHasher $hasher,)
    {
        $this->users = $users;
        $this->hasher = $hasher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByPasswordResetToken($command->token)) {
            throw new DomainException('Токен не найден.');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            AuthKey::generate(),
            $command->password,
            $this->hasher
        );

        $this->users->save($user);
    }
}
