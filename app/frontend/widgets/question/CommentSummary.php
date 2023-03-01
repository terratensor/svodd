<?php

namespace frontend\widgets\question;

use Manticoresearch\ResultSet;use yii\bootstrap5\Widget;use yii\helpers\Html;

class CommentSummary extends Widget
{
    public ?int $pageSize = null;
    public int $page = 1;
    public int $summary = 0;

    public function init()
    {
        if ($this->pageSize === null) {
            $this->pageSize = \Yii::$app->params['questions']['pageSize'];
        }
    }

    public function renderSummary(): string
    {
        $start = ($this->page * $this->pageSize - $this->pageSize) + 1;
        $end = $this->page * $this->pageSize;

        $string = \Yii::t(
            'app',
            'Показано {start} – {end} из {n, plural, =0{Нет комментариев} few{# комментария} many{# комментариев} other{# комментариев}}',
            [
                'n' => $this->summary,
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
