<?php

declare(strict_types=1);

namespace App\helpers;

use App\models\Comment;
use yii\bootstrap5\Html;

class SearchResultsHelper
{
    public static function showUsername(Comment $comment): string
    {
        $str = $comment->highlight['username'][0] ?? $comment->username;

        if ($comment->highlight['avatar_file'] && $comment->highlight['avatar_file'][0]) {
            return Html::tag('mark', $str);
        }
        return $str;
    }
}
