<?php

declare(strict_types=1);

namespace App\repositories\Question;

use App\helpers\SearchHelper;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\Query\In;
use Manticoresearch\Query\MatchPhrase;
use Manticoresearch\Query\MatchQuery;
use Manticoresearch\Query\QueryString;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;

class QuestionRepository
{
    private Client $client;
    public Index $index;
    private Search $search;

    private string $indexName = 'questions';
    public int $pageSize = 20;

    public function __construct(Client $client, $pageSize)
    {
        $this->client = $client;
        $this->index = $this->client->index('questions');
        $this->search = new Search($this->client);
        $this->pageSize = $pageSize;
    }

    /**
     * @param string $queryString
     * @return Search
     * "query_string" accepts an input string as a full-text query in MATCH() syntax
     */
    public function findByQueryStringNew(string $queryString): Search
    {
        $this->search->reset();
        $queryString = SearchHelper::escapingCharacters($queryString);

        $query = new QueryString($queryString);
        $search = $this->index->search($query);

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
            ]
        );

        return $search;
    }

    /**
     * @param string $queryString
     * @return Search
     * "match" is a simple query that matches the specified keywords in the specified fields.
     */
    public function findByQueryStringMatch(string $queryString): Search
    {
        $this->search->reset();
        $query = new MatchQuery($queryString, '*');
        $search = $this->index->search($query);

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
            ]
        );

        return $search;
    }

    /**
     * @param string $queryString
     * @return Search
     * "match_phrase" is a query that matches the entire phrase. It is similar to a phrase operator in SQL.
     */
    public function findByMatchPhrase(string $queryString): Search
    {
        $this->search->reset();
        $query = new MatchPhrase($queryString, '*');
        $search = $this->index->search($query);

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
            ]
        );

        return $search;
    }

    /**
     * @param $queryString String Число или строка чисел через запятую
     * @return Search
     * Поиск по data_id, вопрос или комментарий, число или массив data_id
     */
    public function findByCommentId(string $queryString): Search
    {
        $this->search->reset();

        $result = explode(',', $queryString);

        foreach ($result as $key => $item) {
            $item = (int)$item;
            if ($item == 0) {
                unset($result[$key]);
                continue;
            }
            $result[$key] = $item;
        }

        $query = new In('data_id', $result);
        $search = $this->index->search($query);
        $search->highlight(
            ['text'],
            [
                'limit' => 0,
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
