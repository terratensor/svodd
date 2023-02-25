<?php

declare(strict_types=1);

namespace App\services;

use App\forms\SearchForm;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;

/**
 * Class ManticoreService
 * @packaage App\services
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class ManticoreService
{
    private ?Client $client = null;

    public function index(SearchForm $form, int $page): ResultSet
    {
        $query = $form->query;
        $client = new Client(\Yii::$app->params['manticore']);

        $index = new Index($client);
        $index->setName('questions');
        $index->drop(true);

        $index->create(
            [
                'username' => ['type' => 'text'],
                'role' => ['type' => 'text'],
                'text' => ['type' => 'text'],
                'datetime' => ['type' => 'text'],
                'data_id' => ['type' => 'integer'],
            ],
            [
                'morphology' => 'stem_ru'
            ]
        );

        $file = $this->readFile();
        $topic = json_decode($file, false, 512, JSON_THROW_ON_ERROR);

        foreach ($topic->comments as $comment) {
            $index->addDocument($comment);
        }


        $search = new Search($client);
        $search->setIndex('questions');

        $results = $search
            ->match($query)
            ->highlight(['text'])
            ->offset(($page - 1) * 20)
            ->get();

        return $results;
    }

    private function readFile(): bool|string
    {
        return file_get_contents(__DIR__ . "/../../data/30-qa-question-view-8162.json");
    }
}
