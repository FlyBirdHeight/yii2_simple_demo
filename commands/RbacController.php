<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/22
 * Time: 17:49
 */

namespace app\commands;

use app\rbac\AuthorRole;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{

    public function actionInit(){
        $auth = Yii::$app->authManager;
        $updatePost = $auth->getPermission('updatePost');
        $author = $auth->getRole('author');
        // 添加规则
        $rule = new AuthorRole();
        $auth->add($rule);
// 添加 "updateOwnPost" 权限并与规则关联
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

// "updateOwnPost" 权限将由 "updatePost" 权限使用
        $auth->addChild($updateOwnPost, $updatePost);

// 允许 "author" 更新自己的帖子
        $auth->addChild($author, $updateOwnPost);
    }
}