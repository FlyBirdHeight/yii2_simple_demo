<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 11:46
 */

namespace app\models;


use yii\base\Model;
class UploadForm extends Model
{
    public $images;

    public function rules()
    {
        return [
            [['images'], 'file'],
        ];
    }

}