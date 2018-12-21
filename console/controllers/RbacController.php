<?php
/**
 * Created by PhpStorm.
 * User: anvik
 * Date: 20.12.2018
 * Time: 20:09
 */

namespace console\controllers;
use Yii;
use User;


class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->assign($admin, 1);
    }
    public function actionInitProgrammer()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('programmer');
        $auth->add($admin);
        $auth->assign($admin, 1);
        $auth->addChild($auth->getRole('programmer'), $auth->getRole('admin'));
    }

    public function actionCreatePermission($name, $description)
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission($name);
        $permission->description = $description;
        $auth->add($permission);
    }

    public function actionCreateRole($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($name);
        $auth->add($role);
    }

    public function actionAssignRole($id, $roleName)
    {
        $user = User::findOne($id);
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $auth->assign($role, $user);
    }

}