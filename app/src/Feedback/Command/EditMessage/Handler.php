<?php

namespace App\Feedback\Command\EditMessage;

use App\Auth\Entity\User\Id as UserID;
use App\Auth\Entity\User\UserRepository;
use App\Feedback\Entity\Feedback\FeedbackRepository;
use App\Feedback\Entity\Feedback\Id;

class Handler
{
    private UserRepository $userRepository;
    private FeedbackRepository $feedbackRepository;

    public function __construct(UserRepository $userRepository, FeedbackRepository $feedbackRepository)
    {
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
    }

    public function handle(Command $command): void
    {
        try {
            $feedback = $this->feedbackRepository->get(new Id($command->id));
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }

        try {
            $user = $this->userRepository->get(new UserID($command->user_id));
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }

        if (!$feedback->isForUser($user->getId())) {
            throw new \DomainException('Пользователю запрещено редактировать это сообщение.');
        }

        $feedback->edit($command->text);

        $this->feedbackRepository->save($feedback);
    }
}
