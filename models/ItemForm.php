<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/21
 * Time: 18:21
 */

namespace app\models;


use yii\base\Model;

class ItemForm extends Model
{
    public $name;
    public $description;
    public $price;
    public $num;
    public $avatar;
    public $show_image;

    public function rules()
    {
        return [
            [['name', 'description', 'price', 'num', 'avatar'], 'required'],
            ['price', 'number', 'min' => 0],
            ['num', 'number', 'min' => 0],
        ];
    }

    public function create(){
        $item = new Items();

        $item->name = $this->name;
        $item->description = $this->description;
        $item->price = $this->price;
        $item->num = $this->num;
        $item->avatar = $this->avatar;
        $item->show_image = $this->show_image;

        return $item->save(false)? $item: null;
    }
}