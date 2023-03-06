<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question_stats}}`.
 */
class m230302_093314_create_question_stats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%question_stats}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'question_date' => $this->timestamp(0),
            'number' => $this->integer(),
            'title' => $this->string(512),
            'description' => $this->text(),
            'url' => $this->string(512),
            'comments_count' => $this->integer()->notNull(),
            'last_comment_date' => $this->timestamp(),
            'sort' => $this->integer(),
            'created_at' => $this->timestamp(0),
            'updated_at' => $this->timestamp(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%question_stats}}');
    }
}
