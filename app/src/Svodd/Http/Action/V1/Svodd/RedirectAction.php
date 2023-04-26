<?php

declare(strict_types=1);

namespace App\Svodd\Http\Action\V1\Svodd;

use yii\base\Action;

class RedirectAction extends Action
{
    public function run(): \yii\web\Response
    {
        return $this->controller->redirect(['svodd/index']);
    }
}
