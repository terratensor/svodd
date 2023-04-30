<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\helpers\DateHelper;
use App\models\Comment;
use yii\base\Widget;
use yii\bootstrap5\Html;

class FollowLink extends Widget
{
    public Comment $comment;

    public function run()
    {
        $id = ($this->comment->type === 1) ? $this->comment->data_id : $this->comment->parent_id;
        $link = "https://фкт-алтай.рф/qa/question/view-" . $id;

        $text = $this->comment->type === 1 ? "Перейти к вопросу на ФКТ" : "Перейти к комментарию на ФКТ";

        return Html::a(
            $text,
            $link . "#:~:text=" . DateHelper::showDateFromTimestamp((int)$this->comment->datetime),
            ['target' => '_blank', 'rel' => 'noopener noreferrer']
        );
    }
}
