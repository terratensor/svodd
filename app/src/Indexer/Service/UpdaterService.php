<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use Yii;

class UpdaterService
{

    private ReadFileService $readFileService;
    private JsonUnmarshalService $unmarshalService;
    private TopicService $topicService;

    public function __construct(
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        TopicService $topicService,
    ) {
        $this->readFileService = $readFileService;
        $this->unmarshalService = $unmarshalService;
        $this->topicService = $topicService;
    }

    public function index(): void
    {
        $file = Yii::$app->params['questions']['current']['file'];
        if ($doc = $this->readFileService->readFile($file)){
            echo "parsed: " . $file . "\n";
            $this->changeQuestion($doc);
        }
    }

    private function changeQuestion(string $doc): void
    {
        $topic = $this->unmarshalService->handle($doc);
        $this->topicService->update($topic);
    }
}
