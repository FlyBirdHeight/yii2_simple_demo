<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/22
 * Time: 13:09
 */

namespace app\events;


use yii\base\Event;

class AddOrderCount extends Event
{
    public $user_id;
}