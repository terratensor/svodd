<?php

declare(strict_types=1);

namespace App\Indexer\Service\UpdateDbFromParsedFiles;

use App\Indexer\Service\DirectoryService;
use App\Indexer\Service\Files\RemoveFileService;
use App\Indexer\Service\JsonUnmarshalService;
use App\Indexer\Service\ReadFileService;
use App\Indexer\Service\TopicRenewService;
use App\Question\Entity\Question\QuestionRepository;
use NickBeen\ProgressBar\ProgressBar;

/**
 * Этот обработчик обновляет только уже существующие в БД записи в таблицах question и question_comments
 * Сервис читает файлы, сохраненные парсером, если запись в БД существует, то обновляет эту запись данными из файла,
 * если запись не существует, то пропускает. Пропущенная запись позже будет обработана командой index/updating-index
 */
class Handler
{
    private DirectoryService $directoryService;
    private ReadFileService $readFileService;
    private JsonUnmarshalService $unmarshalService;
    private RemoveFileService $removeFileService;
    private QuestionRepository $questionRepository;
    private TopicRenewService $topicRenewService;

    public function __construct(
        DirectoryService $directoryService,
        ReadFileService $readFileService,
        JsonUnmarshalService $unmarshalService,
        QuestionRepository $questionRepository,
        RemoveFileService $removeFileService,
        TopicRenewService $topicRenewService
    ) {
        $this->directoryService = $directoryService;
        $this->readFileService = $readFileService;
        $this->unmarshalService = $unmarshalService;
        $this->removeFileService = $removeFileService;
        $this->questionRepository = $questionRepository;
        $this->topicRenewService = $topicRenewService;
    }

    public function handle(): void
    {
        $files = $this->directoryService->readDir();

        $key = 100 / count($files);
        $tick = 0;
        echo "Обработано файлов: \r\n";
        $progressBar = new ProgressBar(maxProgress: 100);
        $progressBar->start();

        foreach ($files as $file) {
            if ($doc = $this->readFileService->readFile($file)) {
                $this->updateQuestion($doc);
                $this->removeFileService->handle($file);
            }
            $tick = $tick + $key;
            if ($tick >= 1) {
                $progressBar->tick();
                $tick = 0;
            }
        }
        $progressBar->finish();
    }

    /**
     * @param string $doc
     * @return void
     * Преобразовываем тему в json, проверяем есть ли уже такой вопрос в БД, если нет, то создаем новые записи в БД и поисковом индексе, в противном случае обновляем индекс и БД новыми комментариями, обновляем таблицу статистики
     */
    private function updateQuestion(string $doc): void
    {
        $topic = $this->unmarshalService->handle($doc);
        try {
            $this->questionRepository->getByDataId($topic->question->data_id);
            // Обновляем вопрос и все комментарии вопроса
            $this->topicRenewService->renew($topic);
        } catch (\Throwable $e) {
            echo "The question is skipped: ", $e->getMessage(). "\n";
        }
    }
}
