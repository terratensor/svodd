<?php

declare(strict_types=1);

namespace App\Suggestions\Entity\SearchQuery;

use Manticoresearch\Index;
use Manticoresearch\Client;
use Manticoresearch\Search;
use Manticoresearch\Query\Equals;
use Manticoresearch\Query\BoolQuery;
use Manticoresearch\Query\QueryString;

class SearchQueryRepository
{
    private Client $client;
    public Index $index;
    private Search $search;

    private string $indexName = 'search_queries';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->index = $this->client->index($this->indexName);
    }

    /**
     * @param SearchQuery $searchQuery
     */
    // Add a SearchQuery object to the index.
    // The sid field is removed from the object to avoid
    // indexing it.
    public function add(SearchQuery $searchQuery): void
    {
        $array = get_object_vars($searchQuery);
        if (key_exists("sid", $array)) {
            unset($array["sid"]);
        }
        $this->index->addDocument($array);
    }

    /**
     * Checks if a search query exists in the index.
     *
     * This function constructs a boolean query to search for the specified query string
     * in the index. If the query string is found, it returns true. If an error occurs
     * during the search, it assumes the query exists and returns true.
     *
     * @param string $query The search query string to check for existence.
     * @return bool True if the query exists, false otherwise.
     */
    public function exists(string $query): bool
    {
        try {
            $bool = new BoolQuery();
            $bool->must(new Equals('query', $query));
            $resultSet = $this->index->search($bool)->get();
            if ($resultSet->getTotal() > 0) {
                return true;
            };
        } catch (\Throwable $th) {
            return true;
        }
        return false;
    }

    public function getSuggestions(string $queryString)
    {
        // $this->search->reset();

        $query = new BoolQuery();

        $query->must(new QueryString($queryString));

        $search = $this->index->search($query);
        $search->facet('query');

        return $search;
    }
}
