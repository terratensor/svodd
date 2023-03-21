<?php

use App\Auth\Entity\User\Role;
use yii\db\Migration;
use yii\rbac\Item;
use yii\rbac\Permission;

/**
 * Class m230321_200630_add_roles
 */
class m230321_200630_add_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%auth_items}}', ['type', 'name', 'description', 'created_at', 'updated_at'], [
            [Item::TYPE_ROLE, Role::USER, 'Пользователь', time(), time()],
            [Item::TYPE_ROLE, Role::ADMIN, 'Администратор', time(), time()],
        ]);

        $this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
            [Role::ADMIN, Role::USER],
        ]);
        $this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'auth_users\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%auth_items}}', ['name' => [
            Role::USER,
            Role::ADMIN,
        ]]);
    }
}
