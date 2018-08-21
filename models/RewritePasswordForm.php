<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 14:43
 */

namespace app\models;


use yii\base\Model;

class RewritePasswordForm extends Model
{
    public $old_password;
    public $password;
    public $repeat_password;
    public $id;

    public function rules()
    {
        return [
            [['old_password','password','repeat_password','id'], 'required'],
            [['password','repeat_password'], 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'operator'=> '==='],
            ['old_password','validatePassword']
        ];
    }

    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $user = Users::findOne($this->id);
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, '原密码不正确');
            }
        }
    }

    public function rewritePassword(){

        if (!$this->validate()) {
            return null;
        }

        $user = Users::findOne($this->id);

        $user->setPassword($this->password);

        return $user->save(false) ? $user: null;


    }
}