<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%questions}}`.
 */
class m230302_195733_create_questions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%questions}}', [
            'id' => $this->primaryKey(),
            'data_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'position' => $this->integer(),
            'username' => $this->string(),
            'user_role' => $this->string(),
            'text' => $this->text(),
            'date' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%questions}}');
    }
}
