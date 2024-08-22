<?php

declare(strict_types=1);

namespace App\Bookmark\Entity\Comment;

use App\Auth\Entity\User\Id as UserId;
use App\Auth\Entity\User\User;
use App\behaviors\TimestampBehavior;
use App\Question\Entity\Question\Comment;
use App\Question\Entity\Question\Id as CommentId;
use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $id
 * @property string $user_id
 * @property string $comment_id
 * @property string|null $comment_data_id
 * @property string|null $datetime
 * @property User user
 * @property Comment comment
 */
class Bookmark extends ActiveRecord
{
    public static function create(
        Id $id,
        UserId $user_id,
        string $comment_id,
        int $comment_data_id
    ): self {
        $bookmark = new static();

        $bookmark->id = $id;
        $bookmark->user_id = $user_id;
        $bookmark->comment_id = $comment_id;
        $bookmark->comment_data_id = $comment_data_id;

        return $bookmark;
    }

    public function getComment(): ActiveQuery
    {
        return $this->hasOne(Comment::class, ['data_id' => 'comment_data_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function tableName(): string
    {
        return '{{%comment_bookmarks}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
