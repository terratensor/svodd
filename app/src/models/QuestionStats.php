<?php

namespace App\models;

use DateTimeImmutable;
use Exception;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int|mixed|null $question_id
 * @property int|mixed|null $number
 * @property string|mixed|null $description
 * @property string|mixed|null $url
 * @property int|mixed|null $comments_count
 * @property int|null $updated_at
 */
class QuestionStats extends ActiveRecord
{
    private DateTimeImmutable $date;

    public static function create(int $question_id, int $comments_count, DateTimeImmutable $date): self
    {
        $stats = new static();

        $stats->question_id = $question_id;
        $stats->comments_count = $comments_count;
        $stats->date = $date;

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
        $this->date = $date;
    }

    public function changeCommentsCount(int $newCount, DateTimeImmutable $date): void
    {
        $this->comments_count = $newCount;
        $this->date = $date;
    }

    public static function tableName(): string
    {
        return "{{%question_stats}}";
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('updated_at', $this->date->format('Y-m-d H:i:s'));
        return parent::beforeSave($insert);
    }

    /**
     * @throws Exception
     */
    public function afterFind()
    {
        $this->date = new DateTimeImmutable($this->updated_at);
        parent::afterFind();
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
