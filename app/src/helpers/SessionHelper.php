<?php

declare(strict_types=1);

namespace App\helpers;

use yii\web\Session;

class SessionHelper
{
    public static function svoddUrl(Session $session): array
    {
        return array_merge(['/svodd/view'], $session->get('svodd') ?? []);
    }
}
