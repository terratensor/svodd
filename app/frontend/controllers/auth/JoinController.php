<?php

declare(strict_types=1);

namespace frontend\controllers\auth;

use App\Auth\Http\Action\V1\Auth\Join\ConfirmAction;
use App\Auth\Http\Action\V1\Auth\Join\RequestAction;
use yii\web\Controller;

class JoinController extends Controller
{
    public function actions(): array
    {
        return [
            'request' => [
                'class' => RequestAction::class,
            ],
            'confirm' => [
                'class' => ConfirmAction::class,
            ],
        ];
    }
}
