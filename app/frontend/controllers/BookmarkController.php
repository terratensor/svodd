<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\Bookmark\Http\Action\V1\Bookmark\Comment\CreateAction;
use yii\filters\AccessControl;
use yii\web\Controller;

class BookmarkController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['add', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'add' => [
                'class' => CreateAction::class,
            ],
        ];
    }
}
