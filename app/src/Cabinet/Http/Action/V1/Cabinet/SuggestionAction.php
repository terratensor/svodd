<?php

declare(strict_types=1);

namespace App\Cabinet\Http\Action\V1\Cabinet;

use App\Suggestions\Service\SuggestionService;
use yii\base\Action;

class SuggestionAction extends Action
{
    private $handler;
    public function __construct($id, $controller, SuggestionService $handler, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
    }

    public function run()
    {
        // Запрос переделан под фильтр

        $suggestions = $this->handler->getSuggestions();

        return $this->controller->render('suggestions', ['dataProvider' => $suggestions]);
    }
}
