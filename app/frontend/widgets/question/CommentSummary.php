<?php

namespace frontend\widgets\question;

use Yii;
use yii\bootstrap5\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class CommentSummary extends Widget
{
    public Pagination $pagination;
    private int $totalCount;

    public function init()
    {
        $this->totalCount = $this->pagination->totalCount;
    }

    public function renderSummary(): string
    {
        $totalCount = $this->pagination->totalCount;
        $start = (($this->pagination->getPage() + 1) * $this->pagination->pageSize - $this->pagination->pageSize) + 1;
        $end = ($this->pagination->getPage() + 1) * $this->pagination->pageSize;
        if ($end > $totalCount) {
            $end = $totalCount;
        }

        $string = $this->getTotalString($totalCount, $start, $end);
        return Html::tag('p', $string, ['class' => 'summary']);
    }

    public function run(): string
    {
        return $this->totalCount ? $this->renderSummary() : '';
    }

    public function getTotalString($totalCount, $start, $end): string
    {
        $title = 'Показано записей {start} – {end} из {n}';
        if (Yii::$app->request->pathInfo === 'bookmarks') {
            $title = 'Показано закладок {start} – {end} из {n}';
        }
        $string = \Yii::t(
            'app',
            $title,
            [
                'n' => $totalCount,
                'start' => $start,
                'end' => $end
            ]
        );

        return $string;
    }
}
