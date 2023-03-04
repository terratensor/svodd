<?php

declare(strict_types=1);

namespace App\Contact\Command\SendEmail\Request;

class Command
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $body = '';
}
