<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\forms\SearchForm;
use yii\base\Widget;
use yii\data\Pagination;
use yii\web\View;

class MetaInfo extends Widget
{
    public SearchForm $model;
    public View $view;
    public Pagination $pagination;

    public function run(): void
    {
        $queryParams = \Yii::$app->request->getQueryParams();

        $defaultQuery = '';
        $queryDescription = '';
        if ($this->model->query !== '') {
            $defaultQuery = ' по запросу ' . mb_strtolower($this->model->query);
            $queryDescription = 'Результаты по запросу ' . mb_strtolower($this->model->query) . '. ';
        }

        $page = $this->pagination->getPage() ? $this->pagination->getPage() + 1 : 0;
        $pageSuffix = $page ? ' — cтраница ' . $page : ''; 

        if (isset($queryParams['search']['badge'])) {
            $currentBadge = $queryParams['search']['badge'];

            switch ($currentBadge) {
                case 'aq':
                    $this->view->title = "Вопрос–Ответ$defaultQuery$pageSuffix";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по стенограммам передач «Вопрос–Ответ»$pageSuffix";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                case 'comments':
                    $this->view->title = "Комментарии$defaultQuery$pageSuffix";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по всем комментариям сайта ФКТ помимо соборной темы СВОДД$pageSuffix";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                case 'svodd':
                    $this->view->title = "СВОДД$defaultQuery$pageSuffix";
                    $this->view->params['meta_description'] = $queryDescription . "Поиск по соборной теме СВОДД$pageSuffix";
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->urlManager->createAbsoluteUrl(['site/index', 'search' => [
                        'badge' => $currentBadge
                    ], 'sort' => '-datetime',])]);
                    break;
                default:
                    $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->params['frontendHostInfo']]);

                    $this->view->title = "Поиск$defaultQuery$pageSuffix";

                    if ($this->model->query !== '') {
                        $this->view->params['meta_description'] = 'Результаты поиска по запросу ' . mb_strtolower($this->model->query).$pageSuffix;
                    }
            }
        } else {
            $this->view->title = $this->model->query ? "Результаты поиска$defaultQuery$pageSuffix" : $this->view->title.$pageSuffix;
            $this->view->registerLinkTag(['rel' => 'canonical', 'href' => \Yii::$app->params['frontendHostInfo']]);

            if (\Yii::$app->request->url !== \Yii::$app->homeUrl) {
                $this->view->params['meta_description'] = 'Результаты поиска по запросу ' . mb_strtolower($this->model->query).$pageSuffix;
            }
        }
    }
}
