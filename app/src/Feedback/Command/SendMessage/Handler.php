<?php

namespace App\Feedback\Command\SendMessage;

use App\Auth\Entity\User\Id as UserID;
use App\Auth\Entity\User\UserRepository;
use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Entity\Feedback\FeedbackRepository;
use App\Feedback\Entity\Feedback\Id;
use App\Feedback\Entity\Feedback\Status;

class Handler
{
    private UserRepository $userRepository;
    private FeedbackRepository $feedbackRepository;

    public function __construct(UserRepository $userRepository, FeedbackRepository $feedbackRepository)
    {
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
    }

    public function handle(Command $command): Feedback
    {
        try {
            $user = $this->userRepository->get(new UserID($command->user_id));
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }

        $feedback = Feedback::create(
            Id::generate(),
            $user->getId(),
            Status::active(),
            $command->text
        );

        $this->feedbackRepository->save($feedback);

        return  $feedback;
    }
}
