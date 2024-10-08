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
use App\Indexer\Service\QuestionIndexService;

class Handler
{
    private Client $client;
    public Index $index;
    public Index $conceptIndex;
    private DirectoryService $directoryService;
    private ReadFileService $readFileService;
    private QuestionRepository $questionRepository;
    private JsonUnmarshalService $unmarshalService;
    private TopicService $topicService;
    private RemoveFileService $removeFileService;
    private QuestionIndexService $questionIndexService;

    public function __construct(
        Client $client,
        DirectoryService $directoryService,
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        QuestionRepository $questionRepository,
        TopicService $topicService,
        RemoveFileService $removeFileService,
        QuestionIndexService $questionIndexService,
    ) {
        $this->client = $client;
        $this->index = $this->client->index('questions');
        $this->directoryService = $directoryService;
        $this->readFileService = $readFileService;
        $this->questionRepository = $questionRepository;
        $this->unmarshalService = $unmarshalService;
        $this->topicService = $topicService;
        $this->removeFileService = $removeFileService;
        $this->questionIndexService = $questionIndexService;
    }

    public function handle(string $name = 'questions'): void
    {
        $this->index = $this->client->index($name);
        $this->conceptIndex = $this->client->index(\Yii::$app->params['indexes']['concept']);

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
            $this->conceptIndex->addDocument($topic->question->getSource());
            // Обновляем статистику комментариев в manticoresearch
            $this->questionIndexService->updateCommentsCount(
                $topic->question->data_id,
                count($topic->comments)
            );

            // Добавляем связанные вопросы в поисковый индекс manticore
            foreach ($topic->relatedQuestions as $relatedQuestion) {
                $this->index->addDocument($relatedQuestion->getSource());
                $this->conceptIndex->addDocument($relatedQuestion->getSource());
            }

            // Добавляем комментарии в поисковый индекс manticore
            /** @var Comment $comment */
            foreach ($topic->comments as $key => $comment) {
                $this->index->addDocument($comment->getSource($key));
                $this->conceptIndex->addDocument($comment->getSource($key));
            }
        }
    }
}
