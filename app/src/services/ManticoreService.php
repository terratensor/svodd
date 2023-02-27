<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use App\models\Question;
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
    private Search $search;

    public int $pageSize = 20;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->search = new Search($this->client);
    }

    public function search(SearchForm $form, int $page): ResultSet
    {
        $query = $form->query;
        $this->search->setIndex('questions');

        try {
            return $this->search
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
                ->offset(($page - 1) * $this->pageSize)
                ->get();
        } catch (ResponseException $e) {
            throw new \DomainException('Необходимо создать индекс для поиска.');
        }
    }

    public function question(int $id, string $page): Question
    {
        $this->search->setIndex('questions');

        $search = $this->search->search('');

        $search->filter('parent_id', $id);
        $search->filter('type', 'in', 2, Search::FILTER_NOT);


        $search->sort('type','asc');
        $search->sort('position','asc');

        $search->offset(($page - 1) * $this->pageSize);

        $search->facet('parent_id','group_questions');
        $search->facet('type','group_type');

        $results = $search->get();
//        echo "<pre>";var_dump($results->getFacets()); echo "</pre>"; die();

        $questionBody = $this->getQuestionText($id);
        $linkedQuestions = $this->getLinkedQuestions($id);

        return Question::create($id, $questionBody, $linkedQuestions, $results);
    }

    private function getQuestionText(int $id): ResultSet
    {
        $search = new Search($this->client);
        $search->setIndex('questions');
        $search->search('');
        $search->filter('data_id', $id);
        return $search->get();
    }

    private function getLinkedQuestions(int $id): ResultSet
    {
        $search = new Search($this->client);
        $search->setIndex('questions');

        $search->search('');

        $search->filter('type', 'in', 2, Search::FILTER_AND);
        $search->filter('parent_id', $id);

        $search->limit(200);

        $search->facet('type','group_type');

        return $search->get();
    }
}
