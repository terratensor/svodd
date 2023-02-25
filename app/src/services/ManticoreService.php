<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use Manticoresearch\Client;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;

/**
 * Class ManticoreService
 * @packaage App\services
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class ManticoreService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(SearchForm $form, int $page): ResultSet
    {
        $query = $form->query;
        $search = new Search($this->client);
        $search->setIndex('questions');

        return $search
            ->match($query)
            ->highlight(['text'])
            ->offset(($page - 1) * 20)
            ->get();
    }
}
