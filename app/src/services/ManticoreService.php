<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use Manticoresearch\Client;
use Manticoresearch\Exceptions\ResponseException;
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

        try {
            return $search
                ->match(['query' => $query, 'operator' => 'and'])
                //            ->filter('parent_id', 'in', [12348])
                ->highlight(
                    ['text'],
                    [
                        'limit' => 0,
                        //                    'pre_tags' => '', 'post_tags' => '',
                        //                    'around' => 10,
                        //                    'html_strip_mode' => 'none',
                        'force_passages' => true,
                    ]

                )
                ->offset(($page - 1) * 20)
                ->get();
        } catch (ResponseException $e) {
            throw new \DomainException('Необходимо создать индекс для поиска.');
        }
    }
}
