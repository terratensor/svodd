<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

use App\behaviors\TimestampBehavior;
use App\Question\Entity\Statistic\QuestionStats;
use App\Svodd\Entity\Chart\events\StartCommentDataIDSetter;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Id записи autoincrement
 * @property int $id
 * Номер вопроса question_data_id
 * @property int $question_id
 * Номер темы
 * @property int $topic_number
 * Название темы, отображается в списке, диаграмме
 * @property string $title
 * Дата и время начала отсчета - открытия темы,
 * может не совпадать с датой первого комментария в вопросе
 * @property string $start_datetime
 * Дата и время завершения отсчета - закрытие темы,
 * может не совпадать с датой последнего комментария в вопросе
 * @property string $end_datetime
 * Номер data_id начального комментария в вопросе с даты открытия темы,
 * может не совпадать с датой первого комментария в вопросе
 * @property int $start_comment_data_id
 * Номер data_id завершающего тему комментария, комментарий с хешем СВОДД{topic_number}
 * может не совпадать с датой первого комментария в вопросе
 * @property int $end_comment_data_id
 * Количество комментариев в теме
 * @property int $comments_count
 * Разница - количество комментариев не опубликованных, надо придумать механизм расчета
 * На сайте есть неопубликованные комментарии, номера этих комментариев могут быть известны в процессе парсинга сайта
 * @property int $comments_delta
 * Указывает что данная тема является текущей активной темой, в которой периодически меняется дата и номер последнего (завершающего) комментария
 * @property bool $active
 * Даты создания и обновления записи
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuestionStats $questionStats
 */
class Data extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    public static function create($question_id, $topic_number, $start_comment_data_id = null): self
    {
        $data = new static();
        $data->question_id = $question_id;
        $data->topic_number = $topic_number;
        $data->start_comment_data_id = $start_comment_data_id;

        $data->active = true;

        return $data;
    }

    public function callStartCommentDataIDSetter(): void
    {
        $this->recordEvent(new StartCommentDataIDSetter($this->question_id));
    }

    public function getQuestionStats(): ActiveQuery
    {
        return $this->hasOne(QuestionStats::class, ['question_id' => 'question_id']);
    }

    public function changeActive(): void
    {
        $this->active = !$this->active;
    }

    public static function tableName(): string
    {
        return '{{%svodd_chart_data}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function isActive(): bool
    {
        return $this->active === true;
    }
}
