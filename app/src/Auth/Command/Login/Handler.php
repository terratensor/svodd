<?php

declare(strict_types=1);

namespace App\Auth\Command\Login;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use DomainException;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;

    public function __construct(UserRepository $users, PasswordHasher $hasher)
    {
        $this->users = $users;
        $this->hasher = $hasher;
    }

    public function auth(Command $command): User
    {
        $email = new Email($command->email);

        try {
            $user = $this->users->getByEmail($email);
        } catch (DomainException) {
            throw new DomainException('Email не зарегистрирован.');
        }

        if (!$user->isActive()) {
            throw new DomainException('Пользователь не активен.');
        }

        $user->validatePassword($command->password, $this->hasher);

        return $user;
    }
}
