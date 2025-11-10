<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\models\Comment;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\data\Pagination;
use yii\helpers\Url;

class SearchContext extends Widget
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
    
    /**
     * @var string|null ID пользователя. Если передан - returnUrl = true, если null - returnUrl = false
     */
    public ?string $userId = null;
    
    /**
     * @var bool Приватный параметр, определяется автоматически по наличию userId
     */
    private bool $returnUrl = false;

    public function init(): void
    {
        // Определяем returnUrl на основе наличия userId
        $this->returnUrl = $this->userId !== null && $this->userId !== '';
        
        $this->title = $this->comment->type === Comment::LINKED_QUESTION
            ? (string) $this->comment->parent_id
            : (string) $this->comment->data_id;
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

        // Если нужно вернуть заголовок со ссылкой (для зарегистрированных пользователей)
        if ($this->returnUrl) {
            $link = Html::a(
                $this->title,
                $this->getUrl(),
                ['rel' => 'nofollow noindex']
            );
            return $link;
        }

        // Если нужно вернуть только текст заголовка без ссылки (для незарегистрированных)
        return Html::encode($this->title);
    }
}