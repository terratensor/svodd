<?php

declare(strict_types=1);

namespace frontend\controllers;

class UserguideController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}