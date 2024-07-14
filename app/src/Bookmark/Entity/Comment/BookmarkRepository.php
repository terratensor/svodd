<?php

declare(strict_types=1);

namespace App\Bookmark\Entity\Comment;

use RuntimeException;

class BookmarkRepository
{
    public function get(Id $id): Bookmark
    {
        if (!$bookmark = Bookmark::findOne($id)) {
            throw new \DomainException('Закладка не найдено.');
        }
        return $bookmark;
    }

    public function save(Bookmark $bookmark): void
    {
        if (!$bookmark->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    public function delete(Bookmark $bookmark): void
    {
        if (!$bookmark->delete()) {
            throw new \RuntimeException('Ошибка при удалении записи.');
        }
    }
}
