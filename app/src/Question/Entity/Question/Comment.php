<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use App\behaviors\DateTimeBehavior;
use DateTimeImmutable;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property int $data_id
 * @property int $question_data_id;
 * @property int $type;
 * @property int $position;
 * @property string $username;
 * @property string $user_role;
 * @property string $text;
 * @property int date;
 */
class Comment extends ActiveRecord
{
    public DateTimeImmutable $datetime;

    public static function create(
        Id $id,
        int $data_id,
        int $question_data_id,
        int $position,
        string $username,
        string $user_role,
        string $text,
        DateTimeImmutable $datetime
    ): self {
        $comment = new static();

        $comment->id = $id;
        $comment->data_id = $data_id;
        $comment->question_data_id = $question_data_id;
        $comment->position = $position;
        $comment->username = $username;
        $comment->user_role = $user_role;
        $comment->text = $text;
        $comment->datetime = $datetime;

        return $comment;
    }

    public static function tableName(): string
    {
        return '{{%question_comments}}';
    }

    public function behaviors(): array
    {
        return [
            DateTimeBehavior::class,
        ];
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDatetime(): DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * @param DateTimeImmutable $datetime
     */
    public function setDatetime(DateTimeImmutable $datetime): void
    {
        $this->datetime = $datetime;
    }
}
