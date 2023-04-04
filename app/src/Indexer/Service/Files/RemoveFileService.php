<?php

declare(strict_types=1);

namespace App\Indexer\Service\Files;

use Yii;
use yii\helpers\BaseFileHelper;

/**
 * Сервис для удаления файла после обработки
 */
class RemoveFileService
{
    public function handle(string $file): bool
    {
        $path = BaseFileHelper::normalizePath(Yii::$app->params['questionIndexFolder'] . '/' . $file);
        return unlink($path);
    }
}
