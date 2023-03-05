<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use App\behaviors\DateTimeBehavior;
use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property int $data_id
 * @property int $parent_data_id;
 * @property int $type;
 * @property int $position;
 * @property string $username;
 * @property string $user_role;
 * @property string $text;
 * @property int date;
 *
 * @property Question[] $linkedQuestions
 */
class Question extends ActiveRecord
{
    public DateTimeImmutable $datetime;

    public static function create(
        Id $id,
        int $data_id,
        int $parent_data_id,
        int $position,
        string $username,
        string $user_role,
        string $text,
        DateTimeImmutable $datetime
    ): self {
        $question = new static();

        $question->id = $id;
        $question->data_id = $data_id;
        $question->parent_data_id = $parent_data_id;
        $question->position = $position;
        $question->username = $username;
        $question->user_role = $user_role;
        $question->text = $text;
        $question->datetime = $datetime;

        return $question;
    }

    public function getLinkedQuestions(): ActiveQuery
    {
        return $this->hasMany(Question::class, ['parent_data_id' => 'data_id']);
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
