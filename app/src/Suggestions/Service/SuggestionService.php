<?php

declare(strict_types=1);

namespace App\Suggestions\Service;

use App\Suggestions\Entity\SearchQuery\SearchQuery;
use App\Suggestions\Entity\SearchQuery\SearchQueryRepository;
use App\Suggestions\repositories\SuggestionDataProvider;

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
        $query = trim(mb_strtolower($query));
        if ($query === '') {
            return;
        }
        try {
            $sq = SearchQuery::create($query);
            if (!$this->searchQueryRepository->exists($query)) {
                $this->searchQueryRepository->add($sq);
            };
        } catch (\Throwable $th) {
            return;
        }
    }

    public function getSuggestions()
    {

        $suggestions = $this->searchQueryRepository->getSuggestions('');
        return new SuggestionDataProvider(
            [
                'query' => $suggestions,
                'pagination' => [
                    'pageSize' => 50,
                ],
                // 'sort' => [
                //     'defaultOrder' => [
                //         'datetime' => SORT_DESC,
                //         'position' => SORT_ASC,
                //     ],
                //     'attributes' => [
                //         'type',
                //         'position',
                //         'datetime' => [
                //             'asc' => ['datetime' => SORT_ASC, 'position' => SORT_DESC],
                //             'desc' => ['datetime' => SORT_DESC, 'position' => SORT_ASC],
                //         ],
                //         'comments_count',
                //     ]
                // ],
            ]
        );
    }
}
