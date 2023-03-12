<?php

use yii\db\Migration;

/**
 * Class m230312_120931_add_foreign_keys
 */
class m230312_120931_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-question_comments-question_data_id',
            '{{%question_comments}}',
            'question_data_id',
            '{{%questions}}',
            'data_id'
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
    }
}
