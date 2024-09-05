<?php

declare(strict_types=1);

namespace App\SearchResults\Command\Create;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Entity\User\Id as UserId;
use App\Id\Entity\Id;
use App\SearchResults\Entity\SearchResultRepository;
use App\SearchResults\Entity\SearchResult;

class Handler
{
    private UserRepository $userRepository;
    private SearchResultRepository $searchResultRepository;

    public function __construct(
        UserRepository $userRepository,
        SearchResultRepository $searchResultRepository
    ) {
        $this->userRepository = $userRepository;
        $this->searchResultRepository = $searchResultRepository;
    }

    public function handle(Command $command): void
    {
        try {
            $user = $this->userRepository->get(new UserId($command->user_id));
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }
        $searchResult = SearchResult::create(
            Id::generate(),
            new Id($user->getId()->getValue()),
            $command->short_link
        );
        $this->searchResultRepository->save($searchResult);
    }
}
