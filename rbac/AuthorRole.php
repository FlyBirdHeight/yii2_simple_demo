<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/22
 * Time: 17:37
 */

namespace app\rbac;


use yii\rbac\Rule;

class AuthorRole extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user 用户 ID.
     * @param Item $item 该规则相关的角色或者权限
     * @param array $params 传给 ManagerInterface::checkAccess() 的参数
     * @return boolean 代表该规则相关的角色或者权限是否被允许
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}