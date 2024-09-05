<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%search_results}}`.
 */
class m240905_180247_create_search_results_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%search_results}}', [
            'id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'user_id' => $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid')->notNull(),
            'short_link' => $this->string(255)->notNull(),
        ]);

        $this->addPrimaryKey('search_results_pkey', '{{%search_results}}', 'id');

        $this->addForeignKey(
            'fk-search_results-user_id',
            '{{%search_results}}',
            'user_id',
            '{{%auth_users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-search_results-user_id', '{{%search_results}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%search_results}}');
    }
}
