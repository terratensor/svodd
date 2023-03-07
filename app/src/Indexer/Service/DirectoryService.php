<?php

declare(strict_types=1);

namespace App\Indexer\Service;

class DirectoryService
{
    public function readDir(): array
    {
        $arrFiles = array();

        $handle = opendir(__DIR__ . '/../../../data/test');
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
