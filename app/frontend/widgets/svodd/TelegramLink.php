<?php

declare(strict_types=1);

namespace frontend\widgets\svodd;

use App\Question\Entity\Question\Comment;
use yii\base\Widget;
use yii\helpers\Html;

class TelegramLink extends Widget
{
    public Comment $comment;

    public function run()
    {
        $url = "https://t.me/svoddru";
        $messages = $this->comment->getMessages();
        /**@var $messages \yii\db\ActiveQuery */
        $messages->orderBy(['created_at' => SORT_DESC]);
        $message = $messages->one();
        if ($message) {
            $url = "{$url}/{$message->message_id}";
        }
        return Html::a('@svoddru', $url, ['class' => 'telegram-link', 'target' => '_blank']);
    }
}
