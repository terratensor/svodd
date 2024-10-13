<?php

declare(strict_types=1);

namespace App\Nlp\Token;

class Suggestion
{
    public string $suggest;
    public int $distance;
    public int $docs;
}
