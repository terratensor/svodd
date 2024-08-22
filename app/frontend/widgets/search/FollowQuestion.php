<?php

namespace frontend\widgets\search;

use App\helpers\SvgIconHelper;
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
    public string $title = 'Контекст';
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
        $questionId = $this->comment->type === 1 ? $this->comment->data_id : $this->question_id;

        if (!$questionId) {
            return Html::tag('span', '');
        }

        $string = \Yii::$app->i18n->format(
            '{n, plural, =0{Нет комментариев} =1{Один комментарий} one{# комментарий} few{# комментария} many{# коментариев} other{# комментария}}',
            ['n' => $this->comment->comments_count],
            'ru_RU'
        );
        $linkTitle = $this->comment->type === 1 ? "<span data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" data-bs-title=\"$string\">" .
            ($this->comment->comments_count > 0 ? SvgIconHelper::commentIcon() : SvgIconHelper::modeCommentIcon()) . " {$this->comment->comments_count}" : $this->title;

        $link = Html::a(
            $linkTitle,
            $this->getUrl(),
        );

        return $link;
    }
}
