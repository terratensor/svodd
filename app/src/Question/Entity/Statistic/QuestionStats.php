<?php

namespace App\Question\Entity\Statistic;

use App\behaviors\TimestampBehavior;
use App\Question\Entity\Question\Question;
use App\Svodd\Entity\Chart\Data;
use DateTimeImmutable;
use Exception;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|mixed|null $question_id
 * @property int|mixed|null $number
 * @property int $question_date
 * @property string|mixed|null $title
 * @property string|mixed|null $description
 * @property string|mixed|null $url
 * @property int|mixed|null $comments_count
 * @property int|null $last_comment_date
 * @property int|null $sort
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $last_comment_data_id
 * @property int|null $first_comment_data_id
 * @property Question $question
 * @property Data $svoddData
 */
class QuestionStats extends ActiveRecord
{
    public ?DateTimeImmutable $lastCommentDate = null;
    public ?DateTimeImmutable $questionDate = null;

    public static function create(
        int $question_id,
        int $comments_count,
        ?DateTimeImmutable $lastCommentDate,
        ?DateTimeImmutable $questionDate
    ): self
    {
        $stats = new static();

        $stats->question_id = $question_id;
        $stats->comments_count = $comments_count;
        $stats->lastCommentDate = $lastCommentDate;
        $stats->questionDate = $questionDate;

        $stats->url = \Yii::$app->params['questions']['url-pattern'] . $question_id;

        return $stats;
    }

    public function edit(
        int $number,
        string $description,
        string $url,
        DateTimeImmutable $date
    )
    {
        $this->number = $number;
        $this->description = $description;
        $this->url = $url;
        $this->questionDate = $date;
    }

    /**
     * Изменяет счетчик количества комментариев в вопросе
     * @param int $newCount
     * @param DateTimeImmutable|null $lastCommentDate
     * @return void
     */
    public function changeCommentsCount(
        int $newCount,
        ?DateTimeImmutable $lastCommentDate): void
    {
        $this->comments_count = $newCount;
        $this->lastCommentDate = $lastCommentDate;
    }

    /**
     * Изменяет data_id последнего комментария в вопросе
     * @param int|null $data_id
     * @return void
     */
    public function changeLastCommentDataId(?int $data_id): void
    {
        $this->last_comment_data_id = $data_id;
    }

    /**
     * Изменяет data_id первого комментария к вопросу
     * @param int|null $data_id
     * @return void
     */
    public function changeFirstCommentDataId(?int $data_id): void
    {
        $this->first_comment_data_id = $data_id;
    }

    public function getQuestion(): ActiveQuery
    {
        return $this->hasOne(Question::class, ['data_id' => 'question_id']);
    }

    public function getSvoddData(): ActiveQuery
    {
        return $this->hasOne(Data::class, ['question_id' => 'question_id']);
    }

    public static function tableName(): string
    {
        return "{{%question_stats}}";
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('question_date', $this->questionDate?->format('Y-m-d H:i:s'));
        $this->setAttribute('last_comment_date', $this->lastCommentDate?->format('Y-m-d H:i:s'));
        return parent::beforeSave($insert);
    }

    /**
     * @throws Exception
     */
    public function afterFind()
    {
        $this->questionDate = $this->question_date ? new DateTimeImmutable($this->question_date) : null;
        $this->lastCommentDate = $this->last_comment_date ? new DateTimeImmutable($this->last_comment_date) : null;
        parent::afterFind();
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getQuestionDate(): ?DateTimeImmutable
    {
        return $this->questionDate;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getLastCommentDate(): ?DateTimeImmutable
    {
        return $this->lastCommentDate;
    }
}
