<?php

declare(strict_types=1);

namespace console\controllers;

use App\Svodd\Service\ChartDatetimeSetter;
use Exception;
use yii\console\Controller;

class SvoddController extends Controller
{
    private ChartDatetimeSetter $handler;

    public function __construct($id, $module, ChartDatetimeSetter $handler, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->handler = $handler;
    }

    public function actionDateSetter()
    {
        $message = 'Done!';
        try {
            $this->handler->handle();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }
}
