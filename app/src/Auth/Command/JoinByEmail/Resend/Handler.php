<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Resend;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\JoinConfirmationSender;
use App\Auth\Service\Tokenizer;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $users;
    private JoinConfirmationSender $sender;
    private Tokenizer $tokenizer;

    public function __construct(
        UserRepository $users,
        JoinConfirmationSender $sender,
        Tokenizer $tokenizer,
    )
    {
        $this->users = $users;
        $this->sender = $sender;
        $this->tokenizer = $tokenizer;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        try {
            $user = $this->users->getByEmail($email);
        } catch (DomainException) {
            throw new DomainException('Пользователь уже существует.');
        }

        $date = new DateTimeImmutable();

        $user->resendVerificationEmail($token = $this->tokenizer->generate($date), $date);

        $this->users->save($user);

        $this->sender->send($email, $token);
    }
}
