<?php

declare(strict_types=1);

namespace App\Bookmark\Command\Create;

use App\Auth\Entity\User\Id as UserID;
use App\Auth\Entity\User\UserRepository;
use App\Bookmark\Entity\Comment\Bookmark;
use App\Bookmark\Entity\Comment\BookmarkRepository;
use App\Bookmark\Entity\Comment\Id;
use App\Question\Entity\Question\CommentRepository;

class Handler
{
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;
    private BookmarkRepository $bookmarkRepository;

    public function __construct(
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        BookmarkRepository $bookmarkRepository
    ) {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->bookmarkRepository = $bookmarkRepository;
    }

    public function handle(Command $command): void
    {
        try {
            $user = $this->userRepository->get(new UserID($command->user_id));
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }

        try {
            $comment = $this->commentRepository->getByDataId((int)$command->comment_id);
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }

        $bookmark = $this->bookmarkRepository->getBy($user->id, $comment->id);

        if (!$bookmark) {
            $bookmark = Bookmark::create(
                Id::generate(),
                $user->getId(),
                $comment->id,
                $comment->data_id,
            );

            $this->bookmarkRepository->save($bookmark);
        } else {
            $this->bookmarkRepository->delete($bookmark);
        }
    }
}
