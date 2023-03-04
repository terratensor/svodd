<?php

declare(strict_types=1);

namespace frontend\controllers\auth;

use App\Auth\Http\Action\V1\Auth\LoginAction;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions(): array
    {
        return [
            'login' => [
                'class' => LoginAction::class,
            ],
//            'logout' => [
//                'class' => LogoutAction::class,
//            ],
        ];
    }
}
