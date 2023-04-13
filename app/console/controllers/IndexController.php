<?php
declare(strict_types=1);

namespace console\controllers;


use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
use App\Indexer\Service\IndexerService;
use App\Indexer\Service\IndexFromDB\Handler;
use App\Indexer\Service\StatisticService;
use App\Indexer\Service\UpdateDbFromParsedFiles\Handler as UpdateDbFromParsedFilesHandler;
use App\Indexer\Service\UpdaterService;
use App\Indexer\Service\UpdatingIndex\Handler as UpdatingIndexHandler;
use App\services\Manticore\IndexService;
use Exception;
use yii\console\Controller;

/**
 * Class IndexController
 * @packaage console\controllers
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class IndexController extends Controller
{
    private IndexService $service;
    private IndexerService $indexerService;
    private UpdaterService $updaterService;
    private StatisticService $statisticService;
    private Handler $reindexFromDbHandler;
    private UpdatingIndexHandler $updatingIndexHandler;
    private UpdateDbFromParsedFilesHandler $updateDbFromParsedFilesHandler;

    public function __construct(
        $id,
        $module,
        IndexService $service,
        IndexerService $indexerService,
        UpdaterService $updaterService,
        StatisticService $statisticService,
        Handler $reindexFromDbHandler,
        UpdatingIndexHandler $updatingIndexHandler,
        UpdateDbFromParsedFilesHandler $updateDbFromParsedFilesHandler,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->indexerService = $indexerService;
        $this->updaterService = $updaterService;
        $this->statisticService = $statisticService;
        $this->reindexFromDbHandler = $reindexFromDbHandler;
        $this->updatingIndexHandler = $updatingIndexHandler;
        $this->updateDbFromParsedFilesHandler = $updateDbFromParsedFilesHandler;
    }

    /**
     * Создание пустого поискового индекса
     * @return void
     */
    public function actionCreate(): void
    {
        $message = 'Done!';
        $name = $this->prompt('Введите имя индекса (по умолчанию: questions):');

        $form = new IndexCreateForm();

        $form->load(['name' => $name], '');

        try {
            $this->service->create($form);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * Удаление поискового индекса вместе с данными!
     * @return void
     */
    public function actionDelete(): void
    {
        $message = 'Done!';
        $name = $this->prompt('Введите наименование индекса для удаления:', ['required' => true]);

        $form = new IndexDeleteForm();

        $form->load(['name' => $name], '');

        try {
            $this->service->delete($form);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * @deprecated Использовался при первой инициализации программы, с пустым индексом и базой,
     * Вместо него надо использовать actionUpdatingIndex()
     * @return void
     */
    public function actionIndexer(): void
    {
        $message = 'Done!';
        try {
            $this->indexerService->index('questions');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->stdout($message . PHP_EOL);
    }

    /**
     * @deprecated Не использовать, будет удалена
     * Команда для удаления вопроса $question_id из поискового индекса
     * @return void
     */
    public function actionDeleteQuestion(): void
    {
        $message = 'Done!';

        $question_id = $this->prompt('Введите номер вопроса для удаления из индекса:', ['required' => true]);
        try {
            $this->service->deleteQuestion((int)$question_id);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * @deprecated Не использовать, будет удалена
     * @return void
     */
    public function actionUpdateCurrent(): void
    {
        $message = 'Done!';
        try {
            $this->service->updateQuestion(\Yii::$app->params['questions']['current']['id']);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * @deprecated Не использовать, будет удалена
     * @return void
     */
    public function actionUpdateCurrentComments(): void
    {
        $message = 'Done!';
        try {
            $this->service->updateQuestionComments(\Yii::$app->params['questions']['current']['id']);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * @deprecated Не использовать, будет удалена
     * @return void
     */
    public function actionUpdater(): void
    {
        $message = 'Done!';
        try {
            $this->updaterService->index();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * Обновление статистики по всем вопросам, читает из базы данных questions и обновляет question_stats
     * @return void
     */
    public function actionUpdateStatistic(): void
    {
        $message = 'Done!';
        try {
            $this->statisticService->updateAll();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * Очищает данные в поисковом индексе и переиндексирует заново, читая из базы данных
     * @return void
     */
    public function actionReindexDb(): void
    {
        $message = 'Done!';
        try {
            $this->reindexFromDbHandler->handle();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * Основная команда для чтения файлов, которые сохранил fct-parser.
     * Добавляет новые вопросы в бд и в индекс manticore или обновляет
     * уже существующие вопросы новыми комментариями.
     * Удаляет файлы после обработки.
     * @return void
     */
    public function actionUpdatingIndex(): void
    {
        $message = 'Done!';
        try {
            $this->updatingIndexHandler->handle();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * Команда для чтения файлов, которе сохранил fct-parser. Обновляет уже существующие в БД записи: вопросы и комментарии.
     * Новые записи не добавляет.
     * @return void
     */
    public function actionUpdateDb(): void
    {
        $message = 'Done!';
        try {
            $this->updateDbFromParsedFilesHandler->handle();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }
}
