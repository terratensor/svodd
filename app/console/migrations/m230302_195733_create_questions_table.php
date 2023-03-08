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
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'data_id' => $this->integer()->unique(),
            'parent_data_id' => $this->integer(),
            'position' => $this->integer(),
            'username' => $this->string(512),
            'user_role' => $this->string(),
            'text' => $this->text(),
            'date' => $this->timestamp(),
        ]);

        $this->addPrimaryKey('questions_pkey', '{{%questions}}', 'id');
        $this->addForeignKey(
            'fk-questions-parent_data_id',
            '{{%questions}}',
            'parent_data_id',
            '{{%questions}}',
            'data_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%questions}}');
    }
}
