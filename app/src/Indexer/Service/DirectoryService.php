<?php

declare(strict_types=1);

namespace App\Indexer\Service;

use Yii;

class DirectoryService
{
    public function readDir(): array
    {
        $arrFiles = array();

        $handle = opendir(Yii::$app->params['questionIndexFolder']);
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != ".gitignore") {
                    $arrFiles[] = $entry;
                }
            }
        }
        closedir($handle);

        return $arrFiles;
    }
}
