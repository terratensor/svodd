<?php

use yii\db\Migration;

/**
 * Class m230413_094354_add_avatar_file_columns
 */
class m230413_094354_add_avatar_file_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('questions', 'avatar_file', $this->string(512));
        $this->addColumn('question_comments', 'avatar_file', $this->string(512));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('questions', 'avatar_file');
        $this->dropColumn('question_comments', 'avatar_file');
    }
}
