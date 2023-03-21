<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\AuthKey;
use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\JoinConfirmationSender;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\Tokenizer;
use App\Rbac\Service\RoleManager;
use App\services\TransactionManager;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;
    private PasswordHasher $hasher;
    private Tokenizer $tokenizer;
    private JoinConfirmationSender $sender;
    private TransactionManager $transaction;
    private RoleManager $roleManager;

    public function __construct(
        UserRepository $users,
        RoleManager $roleManager,
        PasswordHasher $hasher,
        Tokenizer $tokenizer,
        JoinConfirmationSender $sender,
        TransactionManager $transaction
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->transaction = $transaction;
        $this->roleManager = $roleManager;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('Пользователь уже существует.');
        }

        $date = new DateTimeImmutable();

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date,
            AuthKey::generate(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate($date)
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roleManager->assign($user->id, new Role(Role::USER));
        });

        $this->sender->send($email, $token);
    }
}
