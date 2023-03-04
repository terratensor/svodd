<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Confirm;

class Command
{
    public string $token = '';
    public string $password = '';
}
