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
        $text = "★ Источник";

        if ($this->comment->type === 4 || $this->comment->type === 5) {
            $id = null;
            $link = $this->comment->url;
            $paragraph = $this->extractAnswerText($this->comment);
            $fragment = $this->extractWords($paragraph);
            return Html::a(
                $text,
                $fragment ? $link . "#:~:text=" . $fragment : $link,
                ['target' => '_blank', 'rel' => 'noopener noreferrer']
            );
        } else {
            $id = ($this->comment->type === 1) ? $this->comment->data_id : $this->comment->parent_id;
            if ($this->comment->type === 3 && $this->comment->parent_id === 0) {
                $link = $this->comment->url;
            } else {
                $link = "https://фкт-алтай.рф/qa/question/view-" . $id;
            }
            return Html::a(
                $text,
                $link . "#:~:text=" . DateHelper::showDateFromTimestamp((int)$this->comment->datetime),
                ['target' => '_blank', 'rel' => 'noopener noreferrer']
            );
        }
    }

    public function extractAnswerText($comment): string
    {
        $pattern = '/<p class="answer">(.*?)<\/p>/s';
        preg_match($pattern, $comment->text, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return "";
        }
    }

    public function extractWords($inputString)
    {
        $pattern = "/\b(?:[a-zA-Zа-яА-Я]+\s){5}\b/u";
        if (preg_match($pattern, $inputString, $matches)) {
            return $matches[0];
        }
        $pattern = "/\b(?:[a-zA-Zа-яА-Я]+\s){4}\b/u";
        if (preg_match($pattern, $inputString, $matches)) {
            return $matches[0];
        }
        $pattern = "/\b(?:[a-zA-Zа-яА-Я]+\s){3}\b/u";
        if (preg_match($pattern, $inputString, $matches)) {
            return $matches[0];
        }
        $pattern = "/\b(?:[a-zA-Zа-яА-Я]+\s){2}\b/u";
        if (preg_match($pattern, $inputString, $matches)) {
            return $matches[0];
        }
        return "";
    }
}
