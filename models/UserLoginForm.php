<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 11:33
 */

namespace app\models;


use yii\base\Model;

class UserLoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            // username 和 password 都是必填项
            [['email', 'password'], 'required'],

            // 用 validatePassword() 验证 password
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Users::findByEmail($this->email);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Users::findByEmail($this->email);
        } else {
            return false;
        }
    }

}