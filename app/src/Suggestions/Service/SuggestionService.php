<?php

declare(strict_types=1);

namespace App\Suggestions\Service;

use App\Suggestions\Entity\SearchQuery\SearchQuery;
use App\Suggestions\Entity\SearchQuery\SearchQueryRepository;

class SuggestionService
{
    private SearchQueryRepository $searchQueryRepository;
    public function __construct(SearchQueryRepository $searchQueryRepository)
    {
        $this->searchQueryRepository = $searchQueryRepository;
    }

    public function handle(string $query): void
    {
        mb_internal_encoding('UTF-8');
        $query = mb_strtolower($query);

        try {
            $sq = new SearchQuery($query);
            if (!$this->searchQueryRepository->exists($query)) {
                $this->searchQueryRepository->add($sq);
            };
        } catch (\Throwable $th) {
            return;
        }
    }
}
