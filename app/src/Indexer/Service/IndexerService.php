<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use App\Indexer\Model\Comment;
use Exception;
use Manticoresearch\Client;
use Manticoresearch\Index;

class IndexerService
{
    private Client $client;
    private JsonUnmarshalService $unmarshalService;
    private DirectoryService $directoryService;
    private ReadFileService $readFileService;
    private TopicService $topicService;

    public function __construct(
        Client $client,
        DirectoryService $directoryService,
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        TopicService $topicService,
    ) {
        $this->client = $client;
        $this->unmarshalService = $unmarshalService;
        $this->directoryService = $directoryService;
        $this->readFileService = $readFileService;
        $this->topicService = $topicService;
    }

    /**
     * @throws Exception
     */
    public function index(string $name): void
    {
        $params = ['index' => $name];
        $this->client->indices()->truncate($params);

        $index = new Index($this->client);
        $index->setName($name);

        $files = $this->directoryService->readDir();
        foreach ($files as $file) {
            if ($doc = $this->readFileService->readFile($file)){
                echo "parsed: " . $file . "\n";
                $this->addQuestion($doc, $index);
            }
        }
    }

    private function addQuestion(string $doc, Index $index): void
    {
        $topic = $this->unmarshalService->handle($doc);

        $this->topicService->create($topic);

        $index->addDocument($topic->question->getSource());

        foreach ($topic->relatedQuestions as $relatedQuestion) {
            $index->addDocument($relatedQuestion->getSource());
        }

        /** @var Comment $comment */
        foreach ($topic->comments as $key => $comment) {
            $index->addDocument($comment->getSource($key));
        }
    }
}
