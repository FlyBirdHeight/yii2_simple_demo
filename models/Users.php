<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property double $order_count
 * @property double $use_money
 * @property double $money
 *
 * @property Address[] $addresses
 * @property Orders[] $orders
 */
class Users extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'name'], 'required'],
            [['order_count', 'use_money', 'money'], 'number', 'default', 'value' => 0],
            [['email', 'name'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['name'], 'unique'],
            [['created_at', 'updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'order_count' => 'Order Count',
            'use_money' => 'Use Money',
            'money' => 'Money',
            'access_token' => 'Access Token',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['access_token'],$fields['password']);
        return $fields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['user_id' => 'id']);
    }

    public static function findByEmail($email){
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }


}
