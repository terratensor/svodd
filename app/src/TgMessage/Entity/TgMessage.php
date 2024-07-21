<?php

declare(strict_types=1);

namespace App\TgMessage\Entity;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $comment_id
 * @property int $message_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class TgMessage extends ActiveRecord
{
    public static function Create(int $comment_id, int $message_id): TgMessage
    {
        $tgMessage = new static();
        $tgMessage->comment_id = $comment_id;
        $tgMessage->message_id = $message_id;
        return $tgMessage;
    }
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'tg_comments_messages';
    }
}
