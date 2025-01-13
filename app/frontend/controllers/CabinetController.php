<?php

declare(strict_types=1);

namespace frontend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use App\Cabinet\Http\Action\V1\Cabinet\IndexAction;
use App\Cabinet\Http\Action\V1\Cabinet\SuggestionAction;

class CabinetController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'suggestions'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
            'suggestions' => [
                'class' => SuggestionAction::class
            ],
        ];
    }
}
