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
     * Установка даты начала и окончания темы, по-текущему data_id открывающего и закрывающего комментария.
     * Запускается после смены активной темы, для того чтобы пересчитать даты последнего комментарий у всех предыдущих тем,
     * в том числе устанавливает дату закрывающего комментария для предпоследней темы.
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
     * Для открытия новой темы, которая будет подключена к странице «Большая СВОДДная тема»
     * и создаст новую запись - строку для диаграммы статистики, необходимо передать следующие параметры:
     * @param string $url адрес страницы вопроса, следующей темы
     * @param string $number номер следующей темы
     * @param string $data_id ИД комментария, открывающего новую тему
     * @return bool|int
     */
    public function actionChangeCurrent(string $url, string $number, string $data_id): bool|int
    {
        $message = 'Done!';
        try {
            $this->service->changeCurrent($url, $number, $data_id);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->stdout($message . PHP_EOL);
    }

    /**
     * Обновление статистики и счетчика кол-ва комментариев в диаграмме
     * @return void
     */
    public function actionUpdateStatistic(): void
    {
        $message = 'Done!';
        try {
            $this->service->updateStatistic();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $this->stdout($message . PHP_EOL);
    }
}
