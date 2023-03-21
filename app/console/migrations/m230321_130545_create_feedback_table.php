<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%feedback}}`.
 */
class m230321_130545_create_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedback}}', [
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'user_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'status' => $this->string(16)->notNull(),
            'text' => $this->text(),
            'created_at' => $this->timestamp(0),
            'updated_at' => $this->timestamp(0)
        ]);
        $this->addPrimaryKey('feedback_pkey', '{{%feedback}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%feedback}}');
    }
}
