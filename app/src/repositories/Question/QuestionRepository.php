<?php

declare(strict_types=1);

namespace App\repositories\Question;

use App\forms\SearchForm;
use App\helpers\SearchHelper;
use App\Nlp\Token\Token;
use App\Nlp\Token\Suggestion;
use App\Svodd\Entity\Chart\Data;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\Query\BoolQuery;
use Manticoresearch\Query\In;
use Manticoresearch\Query\MatchPhrase;
use Manticoresearch\Query\MatchQuery;
use Manticoresearch\Query\QueryString;
use Manticoresearch\Query\Range;
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
        $this->setIndex($this->client->index('questions'));
        $this->search = new Search($this->client);
        $this->pageSize = $pageSize;
    }

    private  function callKeywords($queryString, $indexName): array
    {
        // Запрос для получения ключевых слов токенов и их количества в документах
        $query = "CALL KEYWORDS('$queryString','$indexName',1 as stats)";
        $rawMode = true;
        return $this->client->sql($query, $rawMode);
    }

    /**
     * Query String processor
     *
     * Если запрос пустой или в индексе мало документов со словами, токенированными так же,
     * предложить новый запрос на основе статистики индекса.
     *
     * @param string $queryString
     * @param string $indexName
     * @param array $stopwords
     * @return QueryStucture
     */
    public function queryStringProcessor(string $queryString, string $indexName, array $stopwords): QueryStucture
    {
        $queryStruct = new QueryStucture($queryString);
        $tokens = [];
        $suggestions = [];

        if (empty($queryString)) {
            $queryStruct->setTokens($tokens);
            $queryStruct->setSuggestions($suggestions);
            return $queryStruct;
        }


        // параметр значения количества документов, содержащих исходное слово. По умолчанию 50.
        $param = 50;
        $result = $this->callKeywords($queryString, $indexName);

        // Обрабатываем каждый токен и формируем новый предлагаемф пользователю запрос, основаный на статистике словаря.
        foreach ($result as $row) {

            $token = new Token();
            $token->qpos = (int)$row['qpos'];
            $token->tokenized = $row['tokenized'];
            $token->normalized = $row['normalized'];
            $token->docs = (int)$row['docs'];
            $token->hits = (int)$row['hits'];

            if (!empty($stopwords) && in_array($row['tokenized'], $stopwords)) {
                $token->markAsStopWord(true);
            }

            $tokens[] = $token;

            // Есликоличестов документов в словаре или количество токенов в словаре менее чем $param 
            if ($row['docs'] < $param || $row['hits'] < $param) {

                // Вызываем функция для получения пдосказок по токену
                // https://manual.manticoresearch.com/Searching/Spell_correction#CALL-QSUGGEST,-CALL-SUGGEST
                $subQuery = "CALL SUGGEST('$token->tokenized','$this->indexName')";
                $rawMode = true;
                $callSuggest = $this->client->sql($subQuery, $rawMode);

                // Find the suggestion with the highest docs value, forexmple:
                // CALL SUGGEST('востое','questions');
                // +----------------+----------+------+
                // | suggest        | distance | docs |
                // +----------------+----------+------+
                // | восток         | 1        | 3992 |
                // | востоке        | 1        | 1048 |
                // | востор         | 1        | 1    |
                // | восто          | 1        | 1    |
                // | постое         | 1        | 1    |
                // +----------------+----------+------+
                $suggest = array_reduce($callSuggest, function ($carry, $item) {
                    if ($carry === null || $item['docs'] > $carry['docs']) {
                        return $item;
                    }

                    return $carry;
                }, null);

                $suggestion = new Suggestion();
                $suggestion->suggest = $suggest['suggest'];
                $suggestion->distance = $suggest['distance'];
                $suggestion->docs = $suggest['docs'];

                // fixed Trying to access array offset on value of type null
                if ($suggestion !== null) {

                    $suggestResult = $this->callKeywords($suggestion->suggest, $this->indexName, $param);
                    $suggestToken = new Token();
                    $suggestToken->qpos = (int)$suggestResult[0]['qpos'];
                    $suggestToken->tokenized = $suggestResult[0]['tokenized'];
                    $suggestToken->normalized = $suggestResult[0]['normalized'];
                    $suggestToken->docs = (int)$suggestResult[0]['docs'];
                    $suggestToken->hits = (int)$suggestResult[0]['hits'];

                    $suggestions[] = $suggestToken;
                }
            } else {
                $suggestions[] = $token;
            }
        }

        $queryStruct->setTokens($tokens);
        $queryStruct->setSuggestions($suggestions);

        // var_dump($queryStruct);

        //Если предлагаемый пользователю запрос совпадает с исходным, то возвращаем пустую строку
        return $queryStruct;
    }

    /**
     * @param string $queryString
     * @param string|null $indexName
     * @param SearchForm|null $form
     * @return Search
     * "query_string" accepts an input string as a full-text query in MATCH() syntax
     */
    public function findByQueryStringNew(
        string $queryString,
        ?string $indexName = null,
        ?SearchForm $form = null
    ): Search {
        $this->search->reset();
        if ($indexName) {
            $this->setIndex($this->client->index($indexName));
        }

        $queryString = SearchHelper::processStringWithURLs($queryString);
        $queryString = SearchHelper::escapeUnclosedQuotes($queryString);


        // Запрос переделан под фильтр
        $query = new BoolQuery();

        $query->must(new QueryString($queryString));

        if ($form) {
            $this->applyDateTimeRangeFilter($query, $form);
        }

        $this->applyBadgeFilter($query, $form);

        $search = $this->index->search($query);
        $search->facet('type');

        // Если нет совпадений no_match_size возвращает пустое поле для подсветки
        $search->highlight(
            ['username', 'avatar_file', 'text'],
            [
                'limit' => 0,
                'no_match_size' => 0,
                'pre_tags' => '<mark>',
                'post_tags' => '</mark>'
            ],
        );

        return $search;
    }

    /**
     * @param string $queryString
     * @param string|null $indexName
     * @param SearchForm|null $form
     * @return Search
     * "match" is a simple query that matches the specified keywords in the specified fields.
     */
    public function findByQueryStringMatch(
        string $queryString,
        ?string $indexName = null,
        ?SearchForm $form = null
    ): Search {
        $this->search->reset();
        if ($indexName) {
            $this->setIndex($this->client->index($indexName));
        }

        // Запрос переделан под фильтр
        $query = new BoolQuery();

        $query->must(new MatchQuery($queryString, '*'));

        if ($form) {
            $this->applyDateTimeRangeFilter($query, $form);
        }

        $this->applyBadgeFilter($query, $form);

        $search = $this->index->search($query);
        $search->facet('type');

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
                'no_match_size' => 0,
                'pre_tags' => '<mark>',
                'post_tags' => '</mark>'
            ]
        );

        return $search;
    }

    /**
     * @param string $queryString
     * @param string|null $indexName
     * @return Search
     * "match_phrase" is a query that matches the entire phrase. It is similar to a phrase operator in SQL.
     */
    public function findByMatchPhrase(
        string $queryString,
        ?string $indexName = null,
        ?SearchForm $form = null
    ): Search {
        $this->search->reset();
        if ($indexName) {
            $this->setIndex($this->client->index($indexName));
        }

        // Запрос переделан под фильтр
        $query = new BoolQuery();

        $query->must(new MatchPhrase($queryString, '*'));

        if ($form) {
            $this->applyDateTimeRangeFilter($query, $form);
        }

        $this->applyBadgeFilter($query, $form);

        $search = $this->index->search($query);
        $search->facet('type');

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
                'no_match_size' => 0,
                'pre_tags' => '<mark>',
                'post_tags' => '</mark>'
            ]
        );

        return $search;
    }

    /**
     * @param $queryString String Число или строка чисел через запятую
     * @param string|null $indexName
     * @return Search
     * Поиск по data_id, вопрос или комментарий, число или массив data_id
     */
    public function findByCommentId(
        string $queryString,
        ?string $indexName = null,
        ?SearchForm $form = null
    ): Search {
        $this->search->reset();
        if ($indexName) {
            $this->setIndex($this->client->index($indexName));
        }

        $result = explode(',', $queryString);

        foreach ($result as $key => $item) {
            $item = (int)$item;
            if ($item == 0) {
                unset($result[$key]);
                continue;
            }
            $result[$key] = $item;
        }
        // Запрос переделан под фильтр
        $query = new BoolQuery();

        if (!empty($result)) {
            $query->must(new In('data_id', array_values($result)));
        } else {
            throw new \DomainException("<p>1. Неправильный запрос, при поиске по номерам записей надо указать номер вопроса или комментария, или перечислить номера через запятую</p>
            <p>Например: 44538,44612,44707</p>
            <p>2. Если вы хотите воспользоваться обычным поиском, то включите фильтр <strong>«Обычный поиск»</strong> в настройках поиска</p>");
        }

        if ($form) {
            $this->applyDateTimeRangeFilter($query, $form);
        }

        $this->applyBadgeFilter($query, $form);

        // Выполняем поиск если установлен фильтр или установлен строка поиска
        if ($form->date || $form->query) {
            $search = $this->index->search($query);
            $search->facet('type');
        } else {
            throw new \DomainException('Задан пустой поисковый запрос');
        }

        $search->highlight(
            ['text'],
            [
                'limit' => 0,
                'no_match_size' => 0,
                'pre_tags' => '<mark>',
                'post_tags' => '</mark>'
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

    /**
     * @param Index $index
     */
    public function setIndex(Index $index): void
    {
        $this->index = $index;
    }

    /**
     * Apply a filter by date and time range.
     *
     * @param BoolQuery $query The query to apply the filter to.
     * @param SearchForm $form The form containing the date range.
     * @return BoolQuery The modified query.
     */
    private function applyDateTimeRangeFilter(BoolQuery $query, SearchForm $form): BoolQuery
    {
        if ($form->date_from && $form->date_to) {
            $query->must(new Range('datetime', [
                'gte' => (int) $form->date_from,
                'lte' => (int) $form->date_to,
            ]));
        }

        return $query;
    }


    public function applyBadgeFilter(BoolQuery $query, SearchForm $form): BoolQuery
    {
        $boolQuery = new BoolQuery();
        if ($form && isset($form->badge)) {
            $badge = $form->badge;
            switch ($badge) {
                case "svodd":
                    $query->must($boolQuery->should(
                        new In('parent_id', $this->getSvoddQuestionIds()),
                        new In('data_id', $this->getSvoddQuestionIds())
                    ));
                    break;
                case "aq":
                    $query->must(new In('type', [4, 5]));
                    break;
                case "comments":
                    $query->mustNot(new In('data_id', $this->getSvoddQuestionIds()))
                        ->mustNot(new In('parent_id', $this->getSvoddQuestionIds()))
                        ->must(new In('type', [1, 2, 3]));
                    break;
            }
        }
        return $query;
    }

    /**
     * @return array
     */
    private function getSvoddQuestionIds(): array
    {
        return Data::find()
            ->select(['question_id'])
            ->asArray()
            ->column();
    }
}
