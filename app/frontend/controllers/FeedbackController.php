<?php

namespace frontend\controllers;

use App\Feedback\Http\Action\V1\Feedback\IndexAction;
use yii\web\Controller;

class FeedbackController extends Controller
{
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
        ];
    }
}
