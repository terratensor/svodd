<?php

use yii\db\Migration;

/**
 * Class m230419_091315_change_question_comments_date_field_requierements
 */
class m230419_091315_change_question_comments_date_field_requierements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('question_comments', 'date', 'timestamptz USING date::timestamptz');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('question_comments', 'date', 'timestamp USING date::timestamp');
    }
}
