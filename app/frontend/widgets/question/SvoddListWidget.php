<?php

namespace frontend\widgets\question;

use App\models\QuestionStats;
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

            $title = $model->title;
            if ($title === '') {
                $title = 'Текущая активная тема';
            }
            if ($model->number === null && $model->title === null) {
                $item2 = Html::tag('h5', 'Просмотр вопроса') . $model->url;
            } else {
                $item2 = Html::tag('h5', $model->number . '. ' . $title) . $model->url;
            }
            $item1 = Html::tag('div', $item2, ['class' => 'ms-2 me-auto']) .
             Html::tag('span', $model->comments_count, ['class' => 'badge bg-primary rounded-pill']);
            $item = Html::tag('div', $item1, ['class' => 'd-flex w-100 justify-content-between align-items-start']);
            $link = Html::a($item, ['question-old/view', 'id' => $model->question_id, 'page' => 1], ['class' => 'list-group-item list-group-item-action']);

            $links .= $link;
        }

        return Html::tag('div', $links, ['class' => 'list-group']);
    }
}

