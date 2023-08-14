<?php

declare(strict_types=1);

namespace frontend\controllers\api;

use App\Svodd\Service\QuestionListHandler;
use yii\rest\Controller;

class ListController extends Controller
{
    private QuestionListHandler $handler;

    public function __construct($id, $module, QuestionListHandler $handler, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->handler = $handler;
    }

    public function actionIndex(): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->handler->handle();
    }
}
