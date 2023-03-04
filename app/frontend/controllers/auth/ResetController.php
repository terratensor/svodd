<?php

declare(strict_types=1);

namespace frontend\controllers\auth;

use yii\web\Controller;
use App\Auth\Http\Action\V1\Auth\Reset\Password\RequestAction;

class ResetController extends Controller
{
    public function actions(): array
    {
        return [
            'password-request' => [
                'class' => RequestAction::class,
            ],
//            'confirm' => [
//                'class' => ConfirmAction::class,
//            ],
        ];
    }
}
