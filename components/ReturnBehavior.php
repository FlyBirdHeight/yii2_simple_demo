<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/20
 * Time: 17:03
 */

namespace app\components;

use YII;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\Response;

class ReturnBehavior extends Behavior
{
    //返回类型，通过控制器里的behaviors()配置参数获取值
    public $return_type = '';

    //让行为响应组件的事件触发
    public function events()
    {
        return [
            //控制器方法执行后触发事件，调用returnData函数
            Controller::EVENT_AFTER_ACTION => 'returnData',
        ];
    }

    //返回数据
    public function returnData()
    {
        switch ($this->return_type) {
            case 'json':
                YII::$app->response->format = Response::FORMAT_JSON;
                break;
            case 'xml':
                YII::$app->response->format = Response::FORMAT_XML;
                break;
            case 'html':
                YII::$app->response->format = Response::FORMAT_HTML;
                break;
            default :
                break;
        }
    }

    //定义方法
    public function test() {
        echo 'returnBehavior test ...';
    }
}