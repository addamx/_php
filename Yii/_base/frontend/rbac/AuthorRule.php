<?php

/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/5
 * Time: 18:20
 */
namespace frontend\rbac;

use \yii;
use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user 用户 ID.
     * @param Item $item 该规则相关的角色或者权限
     * @param array $params 传给 ManagerInterface::checkAccess() 的参数      --> Yii 提供了一个快捷方法 yii\web\User::can()
     * @return boolean 代表该规则相关的角色或者权限是否被允许
     */
    /*
     * 应用场景
     if (\Yii::$app->user->can('updatePost', ['post' => $post])) {
        // 更新帖子
     }
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }

}