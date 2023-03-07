<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use Yii;

class ReadFileService
{
    public function readFile(string $file): string
    {
        if (!($file = file_get_contents(Yii::$app->params['questionIndexFolder'] . $file))) {
            throw new \DomainException("Failure to read file: $file");
        }
        return $file;
    }
}
