<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question_comments}}`.
 */
class m230305_134352_create_question_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {

        $this->createTable('{{%question_comments}}', [
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'data_id' => $this->integer(),
            'question_data_id' => $this->integer(),
            'position' => $this->integer(),
            'username' => $this->string(),
            'user_role' => $this->string(),
            'text' => $this->text(),
            'date' => $this->timestamp(),
        ]);

        $this->addPrimaryKey('question_comments_pkey', '{{%question_comments}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%question_comments}}');
    }
}
