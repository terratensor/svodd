<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Comment;
use Manticoresearch\Client;
use Manticoresearch\Index;
use Yii;

class UpdaterService
{
    private Client $client;
    private DirectoryService $directoryService;
    private ReadFileService $readFileService;
    private JsonUnmarshalService $unmarshalService;
    private TopicService $topicService;

    public function __construct(
        Client $client,
        DirectoryService $directoryService,
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        TopicService $topicService,
    ) {
        $this->client = $client;
        $this->directoryService = $directoryService;
        $this->readFileService = $readFileService;
        $this->unmarshalService = $unmarshalService;
        $this->topicService = $topicService;
    }

    public function index(string $name): void
    {
        $index = $this->client->index('questions');

        $files = $this->directoryService->readDir();

        $file = Yii::$app->params['questions']['current']['file'];
        $doc = $this->readFileService->readFile($file);
        echo "parsed: " . $file . "\n";
        // Если не надо делать запись в бд, ставим saveToDb false
//            $this->addQuestion($doc, $index, true);
        $this->changeQuestion($doc, $index, true);


    }

    private function changeQuestion(string $doc, Index $index): void
    {
        $topic = $this->unmarshalService->handle($doc);

        $this->topicService->update($topic, $index);

//        $index->addDocument($topic->question->getSource());
//
//        foreach ($topic->relatedQuestions as $relatedQuestion) {
//            $index->addDocument($relatedQuestion->getSource());
//        }
//
//        /** @var Comment $comment */
//        foreach ($topic->comments as $key => $comment) {
//            $index->addDocument($comment->getSource($key));
//        }
    }
}
