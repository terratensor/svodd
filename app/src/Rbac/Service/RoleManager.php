<?php

namespace App\Rbac\Service;

use App\Auth\Entity\User\Role;
use DomainException;
use yii\rbac\ManagerInterface;

class RoleManager
{
    private ManagerInterface $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function assign($userId, Role $role): void
    {
        if (!$roleName = $this->manager->getRole($role->getName())) {
            throw new DomainException('Role "' . $roleName . '" does not exist.');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($roleName, $userId);
    }
}
