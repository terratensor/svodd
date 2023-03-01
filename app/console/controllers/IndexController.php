<?php
declare(strict_types=1);

namespace console\controllers;


use App\forms\Manticore\IndexCreateForm;
use App\forms\Manticore\IndexDeleteForm;
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

    public function __construct($id, $module, IndexService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
            $this->service->index();
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

    public function actionUpdateCurrent()
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
}
