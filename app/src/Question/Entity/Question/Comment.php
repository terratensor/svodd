<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

use App\behaviors\DateTimeBehavior;
use App\Question\Entity\Statistic\QuestionStats;
use App\Svodd\Entity\Chart\Data;
use DateTimeImmutable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property int $data_id
 * @property int $question_data_id
 * @property int $type
 * @property int $position
 * @property string $username
 * @property string $avatar_file
 * @property string $user_role
 * @property string $text
 * @property int date
 * @property Question $question
 * @property QuestionStats $questionStat
 * @property Data $SvoddData
 */
class Comment extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    public DateTimeImmutable $datetime;

    public static function create(
        Id $id,
        int $data_id,
        int $question_data_id,
        int $position,
        string $username,
        string $avatar_file,
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
        $comment->avatar_file = $avatar_file;
        $comment->user_role = $user_role;
        $comment->text = $text;
        $comment->datetime = $datetime;

        $comment->recordEvent(new events\CommentCreated($data_id, $question_data_id, $text));

        return $comment;
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

    public function getQuestion(): ActiveQuery
    {
        return $this->hasOne(Question::class, ['data_id' => 'question_data_id']);
    }

    public function getQuestionStat(): ActiveQuery
    {
        return $this->hasOne(QuestionStats::class, ['question_id' => 'question_data_id']);
    }

    public function getSvoddData(): ActiveQuery
    {
        return $this->hasOne(Data::class, ['question_id' => 'question_data_id']);
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

    public function getType(): int
    {
        return Type::QUESTION_COMMENT;
    }
}
