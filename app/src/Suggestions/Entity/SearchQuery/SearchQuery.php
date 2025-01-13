<?php

declare(strict_types=1);

namespace App\Suggestions\Entity\SearchQuery;

class SearchQuery
{
    public string $query;
    public string $suggestions;

    public function __construct(string $query)
    {
        $this->suggestions = $query;
        $this->query = $query;
    }
}
