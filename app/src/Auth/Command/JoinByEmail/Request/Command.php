<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use yii\base\Model;

class Command extends Model
{
    public string $email = '';
    public string $password = '';
}
