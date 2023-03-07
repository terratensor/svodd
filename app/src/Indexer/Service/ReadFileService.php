<?php

declare(strict_types=1);

namespace App\Indexer\Service;

class ReadFileService
{
    public function readFile(string $file): string
    {
        if(!$file = file_get_contents(__DIR__ . "/../../../data/test/$file")) {
            throw new \DomainException("Failure to read file: $file");
        }
        return $file;
    }
}
