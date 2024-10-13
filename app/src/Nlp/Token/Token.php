<?php

declare(strict_types=1);

namespace App\Nlp\Token;

class Token
{
    public int $qpos;
    public string $tokenized;
    public string $normalized;
    public int $docs;
    public int $hits;
    public bool $isStopWord;

    public function __construct()
    {
        $this->isStopWord = false;
    }

    public function markAsStopWord(bool $isStopWord): void
    {
        $this->isStopWord = $isStopWord;
    }
}
