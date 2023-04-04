<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use Yii;
use yii\helpers\BaseFileHelper;

class ReadFileService
{
    /**
     * Возвращает прочитанный файл или false если это директория
     * @param string $file
     * @return string|false
     */
    public function readFile(string $file): string|false
    {
        $path = BaseFileHelper::normalizePath(Yii::$app->params['questionIndexFolder'] . '/' . $file);

        if (is_dir($path)) {
            return false;
        }

        if (!($file = file_get_contents($path))) {
            throw new \DomainException("Failure to read file: $file");
        }
        return $file;
    }
}
