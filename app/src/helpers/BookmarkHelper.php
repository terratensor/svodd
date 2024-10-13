<?php

declare(strict_types=1);

namespace App\helpers;

use App\Bookmark\Entity\Comment\Bookmark;

class BookmarkHelper
{
    public static function hasBookamrks(): bool
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        return Bookmark::find()->andWhere(['user_id' => \Yii::$app->user->id])->exists();
    }
}
