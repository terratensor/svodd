<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\forms\SearchForm;
use yii\base\Widget;
use yii\web\View;

class MetaInfo extends Widget
{
    public SearchForm $model;
    public View $view;

    public function run(): void
    {
        $queryParams = \Yii::$app->request->getQueryParams();

        $queryDescription = '';
        if ($this->model->query !== '') {
            $queryDescription = 'Результаты по запросу ' . mb_strtolower($this->model->query) . '. ';
        }

        if (isset($queryParams['search']['badge'])) {
            $currentBadge = $queryParams['search']['badge'];

            switch ($currentBadge) {
                case 'aq':
                    $this->view->title = "Вопрос–Ответ";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по стенограммам передач «Вопрос–Ответ»";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                case 'comments':
                    $this->view->title = "Комментарии";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по всем комментариям сайта ФКТ помимо соборной темы СВОДД";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                case 'svodd':
                    $this->view->title = "СВОДД";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по соборной теме СВОДД";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                default:
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->params['frontendHostInfo']]);

                    if ($this->model->query !== '') {
                        $this->view->params['meta_description'] = 'Результаты по запросу ' . mb_strtolower($this->model->query);
                    }
            }
        } else {
            $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->params['frontendHostInfo']]);

            if (\Yii::$app->request->url !== \Yii::$app->homeUrl) {
                $this->view->params['meta_description'] = 'Результаты по запросу ' . mb_strtolower($this->model->query);
            }
        }
    }
}
