<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use App\behaviors\DateTimeBehavior;
use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property int|null $data_id
 * @property int|null $parent_data_id;
 * @property int $type;
 * @property int $position;
 * @property string $username;
 * @property string $avatar_file;
 * @property string $user_role;
 * @property string $text;
 * @property int date;
 *
 * @property Question[] $linkedQuestions
 * @property Question[] $relatedQuestions
 * @property Comment[] $comments
 */
class Question extends ActiveRecord
{
    public DateTimeImmutable $datetime;

    public static function create(
        Id $id,
        ?int $data_id,
        ?int $parent_data_id,
        int $position,
        string $username,
        string $avatar_file,
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
        $question->avatar_file = $avatar_file;
        $question->user_role = $user_role;
        $question->text = $text;
        $question->datetime = $datetime;

        return $question;
    }

    public function edit(
        string $username,
        string $avatar_file,
        string $user_role,
        string $text,
        DateTimeImmutable $datetime
    ): void {
        $this->username = $username;
        $this->avatar_file = $avatar_file;
        $this->user_role = $user_role;
        $this->text = trim($text);
        $this->datetime = $datetime;
    }

    public function getLinkedQuestions(): ActiveQuery
    {
        return $this->hasMany(Question::class, ['parent_data_id' => 'data_id']);
    }

    public function getRelatedQuestions(): ActiveQuery
    {
        return $this->hasMany(Question::class, ['parent_data_id' => 'data_id']);
    }

    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['question_data_id' => 'data_id']);
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

    /**
     * @return int
     * Возвращает тип используемый в модели Topic из парсера
     */
    public function getType(): int
    {
        if ($this->data_id && $this->parent_data_id === null) {
            return Type::QUESTION;
        } elseif ($this->data_id && $this->parent_data_id) {
            return Type::RELATED_QUESTION;
        }

        return Type::UNDEFINED;
    }
}
