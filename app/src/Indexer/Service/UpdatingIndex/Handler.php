<?php

namespace App\Indexer\Service\UpdatingIndex;

use App\Indexer\Model\Comment;
use App\Indexer\Service\DirectoryService;
use App\Indexer\Service\Files\RemoveFileService;
use App\Indexer\Service\JsonUnmarshalService;
use App\Indexer\Service\ReadFileService;
use App\Indexer\Service\TopicService;
use App\Question\Entity\Question\QuestionRepository;
use Manticoresearch\Client;
use Manticoresearch\Index;

class Handler
{
    private Client $client;
    public Index $index;
    private DirectoryService $directoryService;
    private ReadFileService $readFileService;
    private QuestionRepository $questionRepository;
    private JsonUnmarshalService $unmarshalService;
    private TopicService $topicService;
    private RemoveFileService $removeFileService;

    public function __construct(
        Client $client,
        DirectoryService $directoryService,
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        QuestionRepository $questionRepository,
        TopicService $topicService,
        RemoveFileService $removeFileService,
    )
    {
        $this->client = $client;
        $this->index = $this->client->index('questions');
        $this->directoryService = $directoryService;
        $this->readFileService = $readFileService;
        $this->questionRepository = $questionRepository;
        $this->unmarshalService = $unmarshalService;
        $this->topicService = $topicService;
        $this->removeFileService = $removeFileService;
    }

    public function handle(string $name = 'questions'): void
    {
        $this->index = $this->client->index($name);

        $files = $this->directoryService->readDir();

        foreach ($files as $file) {
            if ($doc = $this->readFileService->readFile($file)) {
                echo "parsed: " . $file . "\n";
                $this->changeQuestion($doc);
                if ($this->removeFileService->handle($file)) {
                    echo "successfully processed and deleted: " . $file . "\n";
                }
            }
        }
    }

    /**
     * @param string $doc
     * @return void
     * Преобразовываем тему в json, проверяем есть ли уже такой вопрос в БД, если нет, то создаем новые записи в БД и поисковом индексе, в противном случае обновляем индекс и БД новыми комментариями, обновляем таблицу статистики
     */
    private function changeQuestion(string $doc): void
    {
        $topic = $this->unmarshalService->handle($doc);

        try {
            $this->questionRepository->getByDataId($topic->question->data_id);
            // Обновляем комментарии вопроса
            $this->topicService->update($topic);
        } catch (\DomainException) {
            // Создаем новый вопрос и записываем в БД
            $this->topicService->create($topic);

            // Добавляем вопрос в поисковый индекс manticore
            $this->index->addDocument($topic->question->getSource());

            // Добавляем связанные вопросы в поисковый индекс manticore
            foreach ($topic->relatedQuestions as $relatedQuestion) {
                $this->index->addDocument($relatedQuestion->getSource());
            }

            // Добавляем комментарии в поисковый индекс manticore
            /** @var Comment $comment */
            foreach ($topic->comments as $key => $comment) {
                $this->index->addDocument($comment->getSource($key));
            }
        }
    }
}
