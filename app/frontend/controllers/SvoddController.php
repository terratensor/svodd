<?php

namespace frontend\controllers;

use App\Svodd\Http\Action\V1\Svodd\IndexAction;
use App\Svodd\Http\Action\V1\Svodd\ViewAction;
use yii\web\Controller;

class SvoddController extends Controller
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
