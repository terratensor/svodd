<?php
declare(strict_types=1);

namespace console\controllers;


use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
use App\Indexer\Service\IndexerService;
use App\Indexer\Service\IndexFromDB\Handler;
use App\Indexer\Service\StatisticService;
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

    public function __construct(
        $id,
        $module,
        IndexService $service,
        IndexerService $indexerService,
        UpdaterService $updaterService,
        StatisticService $statisticService,
        Handler $reindexFromDbHandler,
        UpdatingIndexHandler $updatingIndexHandler,
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
    }

    public function actionCreate()
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

    public function actionDelete()
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

    public function actionIndexer()
    {
        $message = 'Done!';
        try {
//            $this->service->index();
            $this->indexerService->index('questions');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->stdout($message . PHP_EOL);
    }

    public function actionDeleteCurrentQuestion()
    {
        $message = 'Done!';
        try {
            $this->service->deleteQuestion(\Yii::$app->params['questions']['current']['id']);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    /**
     * @return void
     * ToDo не использовать, рассмотреть удаление метода
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

    public function actionUpdateCurrentComments()
    {
        $message = 'Done!';
        try {
            $this->service->updateQuestionComments(\Yii::$app->params['questions']['current']['id']);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    public function actionUpdater()
    {
        $message = 'Done!';
        try {
            $this->updaterService->index('questions');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    public function actionUpdateStatistic()
    {
        $message = 'Done!';
        try {
            $this->statisticService->updateAll();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }

    public function actionReindexDb()
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
     * @return void разбирает файлы парсера и добавляет новые вопросы или обновляет уже сохраненные вопросы
     * Добавляет в индекс новые вопросы и комментарии, из обновленных файлов парсера.
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
}
