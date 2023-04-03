<?php

declare(strict_types=1);

namespace App\helpers;

use yii\helpers\Html;

class VersionHelper
{
    /**
     * Возвращает версию приложения
     * @return string
     */
    public static function version(): string
    {
        $version = 'v0.3.2';
        return Html::a($version, 'https://github.com/audetv/fct-search/releases/latest', ['target' => '_blank']);
    }
}
