<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\Cabinet\Http\Action\V1\Cabinet\IndexAction;
use yii\filters\AccessControl;
use yii\web\Controller;

class CabinetController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
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
        ];
    }
}
