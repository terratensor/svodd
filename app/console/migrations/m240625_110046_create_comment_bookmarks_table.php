<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment_bookmarks}}`.
 */
class m240625_110046_create_comment_bookmarks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment_bookmarks}}', [
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'user_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'comment_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'comment_data_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(0),
            'updated_at' => $this->timestamp(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment_bookmarks}}');
    }
}
