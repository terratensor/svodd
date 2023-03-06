<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\Question\Http\Action\V1\Question\IndexAction;
use App\Question\Http\Action\V1\Question\ViewAction;
use yii\web\Controller;

class QuestionController extends Controller
{
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ];
    }
}
