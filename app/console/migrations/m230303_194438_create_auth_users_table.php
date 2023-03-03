<?php

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_users}}`.
 */
class m230303_194438_create_auth_users_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_users}}', [
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'date' => $this->timestamp(0),
            'email' => $this->string()->unique(),
            'password_hash' => $this->string(),
            'status' => $this->string(16),
            'role' => $this->string(16),
            'new_email' => $this->string(),
            'join_confirm_token_value' => $this->string(),
            'join_confirm_token_expires' => $this->timestamp(0),
            'password_reset_token_value' => $this->string(),
            'password_reset_token_expires' => $this->timestamp(0),
            'new_email_token_value' => $this->string(),
            'new_email_token_expires' => $this->timestamp(0),
        ]);

        $this->addPrimaryKey('auth_users_pkey', '{{%auth_users}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_users}}');
    }
}
