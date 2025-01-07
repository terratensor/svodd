<?php

use yii\db\Migration;

/**
 * Class m250107_192408_create_table_tg_comments_messages_usernames
 */
class m250107_192408_create_table_tg_comments_messages_usernames extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tg_comments_messages_usernames}}', [
            'username' => $this->string()->notNull()->unique(),
        ]);

        $data = [
            ['username' => 'Ян'],
            ['username' => 'Красный'],
            ['username' => '★ Алексей'],
            ['username' => 'Правда Артём'],
            ['username' => 'Бейлибералов Евгений'],
            ['username' => 'macnamara'],
            ['username' => 'Альпеншток'],
            ['username' => 'Наталья Анатольевна'],
            ['username' => 'Игоревич'],
            ['username' => 'Б Ася'],
            ['username' => 'Просто Серёжа'],
            ['username' => 'Георгиев Георгий'],
            ['username' => 'Dogged Contender'],
            ['username' => 'Владимир Юрьевич'],
            ['username' => 'Прилуцкий Игорь Донецк'],
        ];
        $this->batchInsert('{{%tg_comments_messages_usernames}}', ['username'], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tg_comments_messages_usernames}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250107_192408_create_table_tg_comments_messages_usernames cannot be reverted.\n";

        return false;
    }
    */
}
