<?php

declare(strict_types=1);

namespace console\controllers;

use App\Svodd\Service\ChartDatetimeSetter;
use App\Svodd\Service\SvoddService;
use Exception;
use yii\console\Controller;

class SvoddController extends Controller
{
    private ChartDatetimeSetter $handler;
    private SvoddService $service;

    public function __construct(
        $id,
        $module,
        ChartDatetimeSetter $datetimeSetter,
        SvoddService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->handler = $datetimeSetter;
        $this->service = $service;
    }

    public function actionDateSetter(): bool|int
    {
        $message = 'Done!';
        try {
            $this->handler->handle();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->stdout($message . PHP_EOL);
    }

    public function actionChangeCurrent(string $url): bool|int
    {
        $message = 'Done!';
        try {
            $this->service->changeCurrent($url);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->stdout($message . PHP_EOL);
    }
}
