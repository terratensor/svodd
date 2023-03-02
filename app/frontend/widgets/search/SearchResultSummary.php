<?php

namespace frontend\widgets\search;

use yii\base\Widget;
use yii\helpers\Html;

class SearchResultSummary extends Widget
{
    public ?int $pageSize = null;
    public int $page = 1;
    public int $summary = 0;

    public function init()
    {
        $this->page = \Yii::$app->request->get()['page'] ?? 1;

        if ($this->page <= 0) {
            $this->page = 1;
        }


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
        if ($this->summary < 1) {
            return '';
        }
        return $this->renderSummary();
    }
}
