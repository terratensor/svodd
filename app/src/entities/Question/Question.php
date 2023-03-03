<?php

declare(strict_types=1);

namespace App\entities\Question;

use App\behaviors\DateTimeBehavior;
use DateTimeImmutable;
use yii\db\ActiveRecord;

/**
 * @property int $data_id
 * @property int $parent_id;
 * @property int $type;
 * @property int $position;
 * @property string $username;
 * @property string $user_role;
 * @property string $text;
 * @property int date;
 */
class Question extends ActiveRecord
{
    public DateTimeImmutable $datetime;

    public static function create(
        int $data_id,
        int $parent_id,
        int $position,
        string $username,
        string $user_role,
        string $text,
        DateTimeImmutable $datetime
    ): self {
        $question = new static();

        $question->data_id = $data_id;
        $question->parent_id = $parent_id;
        $question->position = $position;
        $question->username = $username;
        $question->user_role = $user_role;
        $question->text = $text;
        $question->datetime = $datetime;

        return $question;
    }

    public static function tableName(): string
    {
        return '{{%questions}}';
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
