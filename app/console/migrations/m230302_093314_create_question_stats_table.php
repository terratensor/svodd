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
            'comments_count' => $this->integer()->notNull(),
            'updated_at' => $this->timestamp(0)->notNull()
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
