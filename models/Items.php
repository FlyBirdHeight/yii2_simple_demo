<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property int $num
 * @property int $sell_count
 * @property string $created_at
 * @property string $updated_at
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['price'], 'number'],
            [['num', 'sell_count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'num' => 'Num',
            'sell_count' => 'Sell Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'avatar' => 'Avatar',
            'show_image' => 'Show Image',
            'version' => 'Version',
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

    public function getOrders(){
        return $this->hasMany(Orders::className(),['id' => 'order_id'])
            ->viaTable('order_item',['item_id' => 'id'])
            ->asArray();
    }
}
