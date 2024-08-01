<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tg_comments_messages}}`.
 */
class m240721_114716_create_tg_comments_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tg_comments_messages}}', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer(),
            'message_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->createIndex('tg_comments_messages_comment_id', '{{%tg_comments_messages}}', 'comment_id');
        $this->createIndex('tg_comments_messages_message_id', '{{%tg_comments_messages}}', 'message_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tg_comments_messages}}');
    }
}
