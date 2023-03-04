<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordResetTokenSender;
use App\Auth\Service\Tokenizer;
use DateTimeImmutable;

class Handler
{
    private UserRepository $users;
    private Tokenizer $tokenizer;
    private PasswordResetTokenSender $sender;

    public function __construct(
        UserRepository $users,
        Tokenizer $tokenizer,
        PasswordResetTokenSender $sender
    ) {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
    }

    public function handle(Command $command): bool
    {
        $email = new Email($command->email);

        $user = $this->users->getByEmail($email);

        $date = new DateTimeImmutable();

        $user->requestPasswordReset(
            $token = $this->tokenizer->generate($date),
            $date
        );

        $this->users->save($user);

        return $this->sender->send($email, $token);
    }
}
