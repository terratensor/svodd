<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use Yii;
use yii\helpers\BaseFileHelper;

class ReadFileService
{
    public function readFile(string $file): string
    {
        $path = BaseFileHelper::normalizePath(Yii::$app->params['questionIndexFolder'] . '/' . $file);

        if (!($file = file_get_contents($path))) {
            throw new \DomainException("Failure to read file: $file");
        }
        return $file;
    }
}
