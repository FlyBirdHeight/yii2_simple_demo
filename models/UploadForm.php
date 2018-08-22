<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 11:46
 */

namespace app\models;


use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
class UploadForm extends Model
{

    public $path = '';

    public $images;

    public function rules()
    {
        return [
            ['images', 'required'],
            ['images', 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,gif,bmp', 'maxSize' => 1024000,],
        ];
    }

    public function upload(){
        if(!$this->validate()){
            return false;
        }

        $randomNumber = microtime() . mt_rand(111111, 999999);
        $filename = md5($randomNumber) . '.' . $this->images->extension;
        $randomFolder = substr($randomNumber, -2);

        $path = '/upload/image/' . $randomFolder . '/' . $filename;
        $fullPath = Yii::getAlias('@app/web') . $path;
        if(!is_dir(dirname($fullPath))){
            FileHelper::createDirectory(dirname($fullPath));
        }
        if(!$this->images->saveAs($fullPath)){
            throw new \yii\base\ErrorException('保存上传文件失败');
        }
        $this->path = $path;
        return true;
    }

}