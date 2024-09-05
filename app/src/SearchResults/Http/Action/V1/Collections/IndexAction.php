<?php

declare(strict_types=1);

namespace App\SearchResults\Http\Action\V1\Collections;

use App\UrlShortener\Service\ViewAllHandler;
use yii\data\ArrayDataProvider;

class IndexAction extends \yii\base\Action
{
    private ViewAllHandler $handler;

    public function __construct($id, $controller, ViewAllHandler $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run()
    {
        $response = $this->handler->handle();
        $links = json_decode($response, true);

        $provider = new ArrayDataProvider([
            'allModels' => $links,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_ASC],
                'attributes' => ['search', 'redirect_count', 'created_at' => [
                    'asc' => ['created_at' => SORT_DESC],
                    'desc' => ['created_at' => SORT_ASC],
                    'label' => 'datetime',
                ]],
            ],
        ]);

        return $this->controller->render('index', ['provider' => $provider]);
    }
}
