<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;

class Handler
{
    private UserRepository $users;

    public function __construct(UserRepository $users) {
        $this->users = $users;
    }

    public function handler(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));
        $this->users->remove
    }
}
