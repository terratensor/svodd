<?php

namespace frontend\controllers;

use App\Feedback\Http\Action\V1\Feedback\DeleteAction;
use App\Feedback\Http\Action\V1\Feedback\IndexAction;
use App\Feedback\Http\Action\V1\Feedback\UpdateAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FeedbackController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'update', 'delete'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
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
            'update' => [
                'class' => UpdateAction::class
            ],
            'delete' => [
                'class' => DeleteAction::class
            ],
        ];
    }
}
