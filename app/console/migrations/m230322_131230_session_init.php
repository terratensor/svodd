<?php

use yii\db\Migration;

/**
 * Class m230322_131230_session_init
 */
class m230322_131230_session_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $dataType = $this->binary();
        $tableOptions = null;

        switch ($this->db->driverName) {
            case 'mysql':
                // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
                break;
            case 'sqlsrv':
            case 'mssql':
            case 'dblib':
                $dataType = $this->text();
                break;
        }

        $this->createTable('{{%session}}', [
            'id' => $this->string()->notNull(),
            'expire' => $this->integer(),
            'data' => $dataType,
            'PRIMARY KEY ([[id]])',
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%session}}');
    }
}
