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

    /**
     * Установка даты начала и окончания темы, по текущему data_id открывающего и закрывающего комментария
     * @return bool|int
     */
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

    /**
     * Смена активной темы СВОДД, передается адрес новой темы
     * @param string $url
     * @return bool|int
     */
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

    /**
     * Обновление статистики и счетчика кол-ва комментариев в диаграмме
     * @return bool|int
     */
    public function actionUpdateStatistic(): bool|int
    {
        $message = 'Done!';
        try {
            $this->service->updateStatistic();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->stdout($message . PHP_EOL);
    }
}
