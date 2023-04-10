<?php

use yii\db\Migration;

/**
 * Class m230410_113518_change_question_comments_fk
 */
class m230410_113518_change_question_comments_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk-question_comments-question_data_id',
            '{{%question_comments}}'
        );

        $this->addForeignKey(
            'fk-question_comments-question_data_id',
            '{{%question_comments}}',
            'question_data_id',
            '{{%questions}}',
            'data_id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-question_comments-question_data_id',
            '{{%question_comments}}'
        );

        $this->addForeignKey(
            'fk-question_comments-question_data_id',
            '{{%question_comments}}',
            'question_data_id',
            '{{%questions}}',
            'data_id'
        );
    }
}
