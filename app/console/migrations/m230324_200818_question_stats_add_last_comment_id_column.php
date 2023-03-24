<?php

use yii\db\Migration;

/**
 * Class m230324_200818_question_stats_add_last_comment_id_column
 */
class m230324_200818_question_stats_add_last_comment_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%question_stats}}', 'last_comment_data_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%question_stats}}', 'last_comment_data_id');
    }
}
