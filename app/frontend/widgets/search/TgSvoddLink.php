<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\models\Comment;
use App\TgMessage\Entity\TgMessage;
use yii\base\Widget;
use yii\helpers\Html;

class TgSvoddLink extends Widget
{
    public Comment $comment;

    public function run(): string
    {
        $url = "https://t.me/svoddru";

        $message = TgMessage::find()->andWhere(['comment_id' => $this->comment->data_id])->one();

        if ($message) {
            $url = "{$url}/{$message->message_id}";
            return Html::a('@svoddru', $url, ['class' => 'telegram-link', 'target' => '_blank', 'rel' => 'noopener noreferrer']);
        }

        return '';
    }
}
