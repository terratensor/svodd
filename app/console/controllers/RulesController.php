<?php

namespace console\controllers;

use App\Rbac\Rules\EditFeedback;
use Yii;
use yii\console\Controller;

class RulesController extends Controller
{
    public function actionBootstrap()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $user = $auth->getRole('user');

        if (!$auth->getRule('editFeedback')) {
            $rule = new EditFeedback();
            $auth->add($rule);
        }

        if (!$auth->getPermission('editOwnFeedback')) {
            $permissionForOwn = $auth->createPermission('editOwnFeedback');
            $permissionForOwn->description = 'Редактирование своих сообщений';
            $permissionForOwn->ruleName = 'editFeedback';
            $auth->add($permissionForOwn);

            $auth->addChild($user, $permissionForOwn);
        }

        if (!$auth->getPermission('editAllFeedback')) {
            $permissionForAll = $auth->createPermission('editAllFeedback');
            $permissionForAll->description = 'Редактирование всех сообщений';
            $auth->add($permissionForAll);
            $auth->addChild($admin, $permissionForAll);
        }

    }
}
