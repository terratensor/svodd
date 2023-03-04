<?php

declare(strict_types=1);

namespace App\Auth\Http\Action\V1\Auth;

use Yii;
use yii\base\Action;
use yii\web\Response;

class LogoutAction extends Action
{
    public function run(): Response
    {
        Yii::$app->user->logout();

        return $this->controller->goHome();
    }
}
