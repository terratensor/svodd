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

        $this->addPrimaryKey('bookmarks_pkey', '{{%comment_bookmarks}}', 'id');

        $this->addForeignKey(
            'fk-comment_bookmarks-comment_id',
            '{{%comment_bookmarks}}',
            'comment_id',
            '{{%question_comments}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comment_bookmarks-user_id',
            '{{%comment_bookmarks}}',
            'user_id',
            '{{%auth_users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-comment_bookmarks-user_id-comment_id', '{{%comment_bookmarks}}', 'comment_data_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-comment_bookmarks-comment_id',
            '{{%comment_bookmarks}}'
        );

        $this->dropForeignKey(
            'fk-comment_bookmarks-user_id',
            '{{%auth_users}}'
        );

        $this->dropTable('{{%comment_bookmarks}}');
    }
}
