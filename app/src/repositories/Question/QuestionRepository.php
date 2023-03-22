<?php

declare(strict_types=1);

namespace App\repositories\Question;

use Manticoresearch\Client;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;

class QuestionRepository
{
    private Client $client;
    private Search $search;
    private string $indexName = 'questions';

    public int $pageSize = 20;

    public function __construct(Client $client, $pageSize)
    {
        $this->client = $client;
        $this->search = new Search($this->client);
        $this->pageSize = $pageSize;
    }

    public function findByQueryString(string $queryString, ?int $page = null): ResultSet
    {
        $search = $this->search->setIndex($this->indexName);

        $search->search($queryString);
        $search->highlight(
            ['text'],
            [
                'limit' => 0,
//                'force_passages' => true,
            ]
        );

//        $search->sort('datetime');

        $search->limit($this->pageSize);

        if ($page) {
            $search->offset(($page - 1) * $this->pageSize);
        }

        $count = $search->get()->getTotal();

        if ($count > 1000) {
            $search->maxMatches($count);
        }

        return $search->get();
    }

    public function findByQueryStringNew(string $queryString): Search
    {
        $this->search->reset();

        $search = $this->search->setIndex($this->indexName);

        $queryString = trim(filter_var($queryString, FILTER_SANITIZE_ENCODED), " \-\n\r\t\v\x00");
        $search->search($queryString);
        $search->highlight(
            ['text'],
            [
                'limit' => 0,
//                'force_passages' => true,
            ]
        );

        return $search;
    }

    public function findCommentsByQuestionId(int $id): Search
    {
        $this->search->reset();

        $search = $this->search->setIndex($this->indexName);

        $search->search('');

        $search->filter('parent_id', $id);
        $search->filter('type', 'in', 2, Search::FILTER_NOT);

        return $search;
    }

    public function findQuestionById(int $id): ResultSet
    {
        $this->search->reset();

        $search = $this->search->setIndex($this->indexName);

        $search->search('');
        $search->filter('data_id', $id);

        return $search->get();
    }

    public function findLinkedQuestionsById(int $id): ResultSet
    {
        $this->search->reset();

        $search = $this->search->setIndex($this->indexName);

        $search->search('');

        $search->filter('type', 'in', 2, Search::FILTER_AND);
        $search->filter('parent_id', $id);

        $search->sort('type', 'asc');
        $search->sort('position', 'asc');

        $count = $search->get()->getTotal();

        $search->limit($count);

        return $search->get();
    }
}
