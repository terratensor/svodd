<?php
declare(strict_types=1);

namespace App\services\Manticore;


use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
use Manticoresearch\Client;
use Manticoresearch\Index;

/**
 * Class IndexService
 * @packaage App\services\Manticore
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class IndexService
{
    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function create(IndexCreateForm $form): void
    {
        $name = $form->name;
        if ($name === '') {
            $name = 'questions';
        }
        $index = new Index($this->client);
        $index->setName($name);
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
    }

    public function delete(IndexDeleteForm $form): void
    {
        $params = [
            'index' => $form->name,
            'body' => ['silent' => true]
        ];

        $this->client->indices()->drop($params);
    }

    public function index(): void
    {
        $index = new Index($this->client);
        $index->setName('questions');
        $file = $this->readFile();
        $topic = json_decode($file, false, 512, JSON_THROW_ON_ERROR);

        foreach ($topic->comments as $comment) {
            $index->addDocument($comment);
        }
    }

    private function readFile(): bool|string
    {
        return file_get_contents(__DIR__ . "/../../../data/30-qa-question-view-8162.json");
    }
}
