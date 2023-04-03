<?php

declare(strict_types=1);

namespace App\helpers;

use yii\helpers\Url;
use yii\web\Session;

class SessionHelper
{
    public static function svoddUrl(Session $session): string
    {
        return Url::to(array_merge(['/svodd/view'], $session->get('svodd') ?? []));
    }
}
