<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $item_count
 * @property double $total
 * @property string $order_code
 * @property int $user_id
 * @property int $address_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OrderItem[] $orderItems
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_count', 'user_id', 'address_id'], 'integer'],
            [['total'], 'number'],
            [['order_code', 'user_id', 'address_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_code'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_count' => 'Item Count',
            'total' => 'Total',
            'order_code' => 'Order Code',
            'user_id' => 'User ID',
            'address_id' => 'Address ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(Items::className(), ['id' => 'item_id'])
            ->viaTable('order_item', ['order_id' => 'id'])
            ->select(['id','name','description','avatar','num'])
            ->asArray();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id'])->select(['email','name','id','order_count']);
    }

    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id'])->select(['id','user_id','phone','code','consignee','residence']);
    }
}
