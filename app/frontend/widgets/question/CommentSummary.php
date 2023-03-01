<?php

namespace frontend\widgets\question;

use yii\bootstrap5\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class CommentSummary extends Widget
{
    public Pagination $pagination;

    public function renderSummary(): string
    {
        $totalCount = $this->pagination->totalCount;
        $start = (($this->pagination->getPage() + 1) * $this->pagination->pageSize - $this->pagination->pageSize) + 1;
        $end = ($this->pagination->getPage() + 1) * $this->pagination->pageSize;
        if ($end > $totalCount) {
            $end = $totalCount;
        }

        $string = \Yii::t(
            'app',
            'Показано {start} – {end} из {n, plural, =0{Нет комментариев} few{# комментария} many{# комментариев} other{# комментариев}}',
            [
                'n' => $totalCount,
                'start' => $start,
                'end' => $end
            ]
        );
        return Html::tag('p', $string, ['class' => 'summary']);
    }

    public function run(): string
    {
        return $this->renderSummary();
    }
}
