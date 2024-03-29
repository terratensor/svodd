<?php

namespace frontend\widgets\question;

use App\helpers\DateHelper;
use App\Question\Entity\Statistic\QuestionStats;
use Exception;
use yii\base\Widget;
use yii\helpers\Html;

class SvoddListWidget extends Widget
{
    /**
     * @var QuestionStats[]
     */
    public array $models;

    public function run()
    {
        $links = '';
        foreach ($this->models as $model) {
            $title = sprintf("%02d", $model->svoddData->topic_number) . '. ';

            if ($model->svoddData->isActive()) {
                $title .= 'Текущая активная тема';
            } else {
                try {
                    $title .= DateHelper::showDateFromString($model->svoddData->start_datetime);
                } catch (Exception $e) {
                    $title .= 'Дата не установлена';
                }
            }

            $item = Html::tag('h5', $title) . $model->url;

            $item = Html::tag('div', $item, ['class' => 'ms-2 me-auto']);
            $item .= Html::tag('span', $model->comments_count, ['class' => 'badge bg-secondary rounded-pill']);

            $item = Html::tag('div', $item, ['class' => 'd-flex w-100 justify-content-between align-items-start']);

            $link = Html::a($item, ['question/view', 'id' => $model->question_id, 'page' => 1], [
                'class' => 'list-group-item list-group-item-action',
            ]);

            $links .= $link;
        }

        return Html::tag('div', $links, ['class' => 'list-group mb-4']);
    }
}

