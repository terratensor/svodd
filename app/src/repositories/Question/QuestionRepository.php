<?php

declare(strict_types=1);

namespace App\repositories\Question;

use App\forms\SearchForm;
use App\helpers\SearchHelper;
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
    // параметр значения количества документов, содержащих исходное слово. По умолчанию 50.
    public int $param = 50;

    public function __construct(Client $client, $pageSize)
    {
        $this->client = $client;
        $this->setIndex($this->client->index('questions'));
        $this->search = new Search($this->client);
        $this->pageSize = $pageSize;
    }

    /**
     * Suggest a search query based on the given string and index name.
     *
     * The function takes a string and an index name as input and returns a suggested search query based on the
     * statistics of the dictionary.
     *
     * If the input string is empty or contains special characters used in full-text search, the function returns an empty string.
     *
     * The function processes each token and forms a new suggested query based on the statistics of the dictionary.
     * If the number of documents in the dictionary or the number of tokens in the dictionary is less than $param,
     * the function calls the CALL SUGGEST function to get suggestions for the token.
     * The function then finds the suggestion with the highest docs value and adds it to the suggested query.
     *
     * If the suggested query is the same as the input string, the function returns an empty string.
     *
     * @param string $queryString The input string.
     * @param string $indexName The name of the index.
     *
     * @return string The suggested search query.
     */
    public function queryStringSuggestor(string $queryString, string $indexName): string
    {
        // Если строка пустая или содержит символы, используемые в полнотекстовом поиске, то возвращаем пустую строку 
        if (empty($queryString) || SearchHelper::containsSpecialChars($queryString)) {
            return '';
        }

        // Обрабатываем строку из латинской раскладки в кирилическую
        // TODO для латинской раскладки предлангать 2 варианта запроса, латиница, кирилица
        $queryTransformedString = SearchHelper::transformString($queryString);

        $result = $this->callKeywords($queryTransformedString, $indexName);

        $suggestQueryString = '';
        // Обрабатываем каждый токен и формируем новый предлагаемый пользователю запрос, основаный на статистике словаря.
        foreach ($result as $row) {
            // Если количестов документов в словаре или количество токенов в словаре менее чем $param 
            if ($row['docs'] < $this->param || $row['hits'] < $this->param) {
                $token = $row['tokenized'];
                // Вызываем функция для получения пдосказок по токену
                // https://manual.manticoresearch.com/Searching/Spell_correction#CALL-QSUGGEST,-CALL-SUGGEST
                $subQuery = "CALL SUGGEST('$token','$this->indexName')";
                $rawMode = true;
                $suggestions = $this->client->sql($subQuery, $rawMode);

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
                $suggestion = array_reduce($suggestions, function ($carry, $item) {
                    if ($carry === null || $item['docs'] > $carry['docs']) {
                        return $item;
                    }

                    return $carry;
                }, null);

                // fixed Trying to access array offset on value of type null
                if ($suggestion !== null) {
                    $suggestQueryString .= $suggestion['suggest'] . ' ';
                }
            } else {
                $suggestQueryString .= $row['tokenized'] . ' ';
            }
        }
        //Если предлагаемый пользователю запрос совпадает с исходным, то возвращаем пустую строку
        return $queryString === $suggestQueryString ? '' : $suggestQueryString;
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

    private function callKeywords(string $queryString, $indexName): array
    {
        // Запрос для получения ключевых слов токенов и их количества в документах
        $query = "CALL KEYWORDS('$queryString','$indexName',1 as stats)";
        $rawMode = true;
        $result = $this->client->sql($query, $rawMode);
        return $result;
    }

    /**
     * Get total number of indexed documents in index
     *
     * @return int
     */
    public function getTotalIndexedDocuments(): int
    {        
        try {
            $index_status = $this->client->index($this->indexName)->status();
            if (array_key_exists('indexed_documents', $index_status)) {
                $total_docs = $index_status['indexed_documents'];
                return (int)$total_docs;
            }
        } catch (\Exception $e) {            
            // Handle the exception, e.g. logging.
        }

        // If we're here, something went wrong. Return 0.
        return 0;
    }
}
