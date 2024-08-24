<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\Bookmark\Http\Action\V1\Bookmark\Comment\CreateAction;
use App\Bookmark\Http\Action\V1\Bookmark\Comment\ViewAction;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BookmarkController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }
    public function actions(): array
    {
        return [
            'index' => [
                'class' => CreateAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ];
    }
}
