<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Question\Entity\Question\Question;
use App\Indexer\Model\Comment;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Manticoresearch\Query\In;
use Yii;

class QuestionIndexService
{
    private Client $client;
    public Index $index;
    public Index $conceptIndex;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->index = $this->client->index(Yii::$app->params['indexes']['common']);
        $this->conceptIndex = $this->client->index(Yii::$app->params['indexes']['concept']);
    }

    public function addDocument(Comment $parsedComment, ?int $key = null): void
    {
        // Находим документ по data_id в индексе
        $query = new In('data_id', [$parsedComment->data_id]);

        $search = $this->index->search($query);
        $conceptSearch = $this->conceptIndex->search($query);

        // Если записей найдено больше нуля и есть id записи в индексе.
        // Получаем ид, удаляем документ по ид
        if ($search->get()->count() > 0 && $search->get()->current()->getId()) {
            $id = $search->get()->current()->getId();
            $this->index->deleteDocument($id);
            echo "удален документ #$id в индексе комментарий #$parsedComment->data_id \r\n";
        }

        // То же самой для индекса поиска по словарю концептуальных терминов
        // Если записей найдено больше нуля и есть id записи в индексе.
        // Получаем ид, удаляем документ по ид
        if ($conceptSearch->get()->count() > 0 && $conceptSearch->get()->current()->getId()) {
            $id = $conceptSearch->get()->current()->getId();
            $this->conceptIndex->deleteDocument($id);
            echo "удален документ #$id в concept индексе комментарий #$parsedComment->data_id \r\n";
        }

        // Добавляем документ в индекс
        $this->index->addDocument($parsedComment->getSource($key));
        $this->conceptIndex->addDocument($parsedComment->getSource($key));
        echo "добавлен в индекс комментарий #$parsedComment->data_id \r\n";
    }

    public function updateCommentsCount(int $data_id, int $comments_count): void
    {
        // Находим документ по data_id в индексе
        $query = new In('data_id', [$data_id]);

        $search = $this->index->search($query);
        $conceptSearch = $this->conceptIndex->search($query);

        // Если записей найдено больше нуля и есть id записи в индексе.
        // Получаем ид, обновляем документ по ид
        if ($search->get()->count() > 0 && $search->get()->current()->getId()) {
            $id = $search->get()->current()->getId();
            $this->index->updateDocument([
                'comments_count' => $comments_count,
            ], $id);
            echo "обновлен счётчик комменатриев #$id в индексе комментарий #$data_id \r\n";
        }

        // То же самой для индекса поиска по словарю концептуальных терминов
        // Если записей найдено больше нуля и есть id записи в индексе.
        // Получаем ид, обновляем документ по ид
        if ($conceptSearch->get()->count() > 0 && $conceptSearch->get()->current()->getId()) {
            $id = $conceptSearch->get()->current()->getId();
            $this->conceptIndex->updateDocument([
                'comments_count' => $comments_count,
            ], $id);
            echo "обновлен счётчик комменатриев #$id в concept индексе комментарий #$data_id \r\n";
        }
    }
}
