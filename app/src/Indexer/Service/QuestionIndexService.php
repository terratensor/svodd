<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Comment;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\Query\In;

class QuestionIndexService
{
    private Client $client;
    public Index $index;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->index = $this->client->index('questions');
    }

    public function addDocument(Comment $parsedComment, ?int $key = null): void
    {
        $query = new In('data_id', [$parsedComment->data_id]);
        $search = $this->index->search($query);


        if ($search->get()->current()->getId()) {
            $id = $search->get()->current()->getId();
            $this->index->deleteDocument($id);
            echo "удален документ #$id в индексе комментарий #$parsedComment->data_id \r\n";
        }

        $this->index->addDocument($parsedComment->getSource($key));
        echo "добавлен в индекс комментарий #$parsedComment->data_id \r\n";
    }
}
