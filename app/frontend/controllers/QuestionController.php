<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\Question\Http\Action\V1\Question\ViewAction;
use yii\web\Controller;

class QuestionController extends Controller
{
    public function actions(): array
    {
        return [
            'view' => [
                'class' => ViewAction::class,
            ],
        ];
    }
}
