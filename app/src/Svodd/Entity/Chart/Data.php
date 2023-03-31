<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

use App\Question\Entity\Statistic\QuestionStats;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Id записи autoincrement
 * @property int id
 * Номер вопроса question_data_id
 * @property int question_id
 * Номер темы
 * @property int topic_number
 * Название темы, отображается в списке, диаграмме
 * @property string title
 * Дата и время начала отсчета - открытия темы,
 * может не совпадать с датой первого комментария в вопросе
 * @property string start_datetime
 * Дата и время завершения отсчета - закрытие темы,
 * может не совпадать с датой последнего комментария в вопросе
 * @property string end_datetime
 * Номер data_id начального комментария в вопросе с даты открытия темы,
 * может не совпадать с датой первого комментария в вопросе
 * @property int start_comment_data_id
 * Номер data_id завершающего тему комментария, комментарий с хешем СВОДД{topic_number}
 * может не совпадать с датой первого комментария в вопросе
 * @property int end_comment_data_id
 * Количество комментариев в теме
 * @property int comments_count
 * Разница - количество комментариев не опубликованных, надо придумать механизм расчета
 * На сайте есть неопубликованные комментарии, номера этих комментариев могут быть известны в процессе парсинга сайта
 * @property int comment_delta
 * Указывает что данная тема является текущей активной темой, в которой периодически меняется дата и номер последнего (завершающего) комментария
 * @property bool active
 * Даты создания и обновления записи
 * @property int created_at
 * @property int updated_at
 *
 * @property QuestionStats $questionStats
 */
class Data extends ActiveRecord
{
    public static function create()
    {

    }

    public function getQuestionStats(): ActiveQuery
    {
        return $this->hasOne(QuestionStats::class, ['question_id' => 'question_id']);
    }

    public static function tableName(): string
    {
        return '{{%svodd_chart_data}}';
    }
}
