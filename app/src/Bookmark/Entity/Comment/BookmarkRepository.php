<?php

declare(strict_types=1);

namespace App\Bookmark\Entity\Comment;

use RuntimeException;
use yii\db\ActiveRecord;

class BookmarkRepository
{
    public function get(Id $id): Bookmark
    {
        if (!$bookmark = Bookmark::findOne($id)) {
            throw new \DomainException('Закладка не найдена.');
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

    public function getBy(string $user_id, string $comment_id): Bookmark|null|ActiveRecord
    {
        return Bookmark::find()->andWhere(['user_id' => $user_id, 'comment_id' => $comment_id])->one();
    }
}
