<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 17:15
 */

namespace app\components;


use Yii;

class Alisaes
{
    public function getUpload(){
        Yii::setAlias('@image', 'upload/image');
        return Yii::getAlias('@image');
    }
}