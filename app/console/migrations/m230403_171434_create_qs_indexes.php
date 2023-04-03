<?php

use yii\db\Migration;

/**
 * Class m230403_171434_create_qs_indexes
 */
class m230403_171434_create_qs_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('question_stats_number', '{{%question_stats}}', 'number');
        $this->createIndex('question_comments_date', '{{%question_comments}}', 'date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('question_stats_number', '{{%question_stats}}');
        $this->dropIndex('question_comments_date', '{{%question_comments}}');
    }
}
