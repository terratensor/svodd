<?php

namespace frontend\widgets\search;

use App\models\Comment;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\data\Pagination;
use yii\helpers\Url;

class FollowQuestion extends Widget
{
    /**
     * @var string
     */
    public string $title = 'Посмотреть вопрос';
    /**
     * @var Comment
     */
    public Comment $comment;
    /**
     * @var array|mixed
     */
    protected mixed $question_id;
    /**
     * @var array|mixed
     */
    private mixed $position;

    public Pagination $pagination;

    public function init(): void
    {
        $this->question_id = $this->comment->parent_id;
        $this->position = $this->comment->position;
    }

    public function getUrl(): string
    {
        $total = ceil($this->comment->position / $this->pagination->pageSize);
        return Url::to(
            [
                'question/view',
                'id' => $this->comment->type === 1 ? $this->comment->data_id : $this->question_id,
                'page' => $total,
                'c' => $this->position,
                '#' => $this->position
            ]
        );
    }

    public function run(): string
    {
        $id = $this->comment->type === 1 ? $this->comment->data_id : $this->question_id;
        if (!$id) {
            return '<span></span>';
        }
        return Html::a(
            $this->title,
            $this->getUrl(),
        );
    }
}
