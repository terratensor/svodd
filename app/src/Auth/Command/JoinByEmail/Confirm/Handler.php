<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
    
    public function handle(Command $command): User
    {
        if (!$user = $this->users->findByJoinConfirmToken($command->token)) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmJoin($command->token, new DateTimeImmutable());

        $this->users->save($user);

        return $user;
    }
}
