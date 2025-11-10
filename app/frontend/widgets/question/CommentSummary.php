<?php

namespace frontend\widgets\question;

use Yii;
use yii\bootstrap5\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class CommentSummary extends Widget
{
    public Pagination $pagination;
    public ?int $totalCount = null;

    public function init()
    {
        parent::init();
        
        // Если totalCount не передан явно, используем значение из пагинации
        if ($this->totalCount === null) {
            $this->totalCount = $this->pagination->totalCount;
        }
    }

    public function renderSummary(): string
    {
        $totalCount = $this->totalCount;
        $start = (($this->pagination->getPage() + 1) * $this->pagination->pageSize - $this->pagination->pageSize) + 1;
        $end = ($this->pagination->getPage() + 1) * $this->pagination->pageSize;
        
        // Если end превышает общее количество, корректируем
        if ($end > $totalCount) {
            $end = $totalCount;
        }
        
        // Если start превышает общее количество (например, при последней странице с малым количеством элементов)
        if ($start > $totalCount) {
            $start = $totalCount;
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