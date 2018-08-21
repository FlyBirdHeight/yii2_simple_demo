<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 15:57
 */

namespace app\models;


use yii\base\Model;

class CreateAddressForm extends Model
{
    public $user_id;
    public $code;
    public $consignee;
    public $phone;
    public $residence;

    public function rules()
    {
        return [
            [['user_id', 'consignee', 'phone', 'residence'], 'required'],
//            ['phone', 'match', 'pattern' => '^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$'],
            ['user_id', 'findUser']
        ];
    }

    public function findUser($attribute,$params){
        if (!$this->hasErrors()) {
            $user = Users::findOne($this->user_id);
            if (!$user) {
                $this->addError($attribute, '无该用户');
            }
        }
    }

    public function create(){
        $address = new Address();

        $address->user_id = $this->user_id;
        $address->code = $this->code;
        $address->consignee = $this->consignee;
        $address->phone = $this->phone;
        $address->residence = $this->residence;

        return $address->save(false)? $address: null;
    }
}