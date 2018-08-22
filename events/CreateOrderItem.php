<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/22
 * Time: 13:56
 */

namespace app\events;


use yii\base\Event;

class CreateOrderItem extends Event
{
    public $order_item;
}