<?php

declare(strict_types=1);

namespace App\Search\Command\ToggleSearchSettings\Request;

use yii\web\Session;

class Command
{
    public string $value = '';
    /**
     * @var mixed|object|Session|null
     */
    public mixed $session;
}
