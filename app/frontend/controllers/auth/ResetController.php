<?php

declare(strict_types=1);

namespace frontend\controllers\auth;

use App\Auth\Http\Action\V1\Auth\Reset\Password\ConfirmAction;
use App\Auth\Http\Action\V1\Auth\Reset\Password\RequestAction;
use yii\web\Controller;

class ResetController extends Controller
{
    public function actions(): array
    {
        return [
            'password-request' => [
                'class' => RequestAction::class,
            ],
            'password-confirm' => [
                'class' => ConfirmAction::class,
            ],
        ];
    }
}
