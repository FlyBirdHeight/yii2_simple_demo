<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 11:54
 */

namespace app\models;


use yii\base\Model;

class RegisterForm extends Model
{
    public $email;
    public $password;
    public $name;

    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required', 'message' => '用户名不可以为空'],
            ['name', 'unique', 'targetClass' => 'app\models\Users', 'message' => '用户名已存在.'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => '邮箱不可以为空'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\Users', 'message' => 'email已经被设置了'],

            ['password', 'required', 'message' => '密码不可以为空'],
            ['password', 'string', 'min' => 6, 'tooShort' => '密码至少填写6位'],
//            [['order_count', 'use_money', 'money'], 'default', 'value' => 0],
//            [['created_at', 'updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    public function register(){
        if (!$this->validate()) {
            return null;
        }

        $user = new Users();
        $user->name = $this->name;
        $user->email = $this->email;

        $user->setPassword($this->password);

        $user->generateAuthKey();
        $user->generateAccessToken();

        return $user->save();

//        $auth = \Yii::$app->authManager;
//        $authorRole = $auth->getRole('admin');
//        $auth->assign($authorRole, $user->getId());
    }
}