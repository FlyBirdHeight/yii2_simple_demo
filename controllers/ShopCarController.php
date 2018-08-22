<?php
/**
 * Created by PhpStorm.
 * User: adsionli
 * Date: 2018/8/22
 * Time: 9:47
 */

namespace app\controllers;


use app\components\ReturnBehavior;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ShopCarController extends Controller
{
    public $enableCsrfValidation = false;

    private $redis;
    private $post;

    public function init()
    {
        $this->redis = \Yii::$app->redis;
        $this->post = \Yii::$app->request->post();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'returnBehavior' => [
                'class' => ReturnBehavior::className(),
                'return_type' => 'json',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['POST'],
                    'delete' => ['POST'],
                    'get' => ['GET'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $key = 'user:'.$this->post['user_id'];
        if ($this->redis->hexists($key, 'car')){
            $this->redis->hdel($key,'car');
            $this->redis->hset($key,$this->post['item_id'],json_encode(['num'=>$this->post['num'],'total'=>$this->post['total']]));
            return ['status' => 'success', 'response' => 'success'];
        }else{
            $this->redis->hset('user:'.$this->post['user_id'],$this->post['item_id'],json_encode(['num'=>$this->post['num'],'total'=>$this->post['total']]));
            return ['status' => 'success', 'response' => 'success'];
        }
    }

    public function actionDelete(){
        $key = 'user:'.$this->post['user_id'];
        if ($this->redis->hexists($key,$this->post['item_id'])){
            $this->redis->hdel($key,$this->post['item_id']);
            if ($this->redis->exists($key)){
                $data = $this->change($this->post['user_id']);
                return ['status' => 'success', 'response' => $data];
            }else{
                $this->redis->hset($key,'car','none');
                $data = $this->change($this->post['user_id']);
                return ['status' => 'success', 'response' => $data];
            }
        }else{
            return ['status' => 'error', 'response' => 'error'];
        }
    }

    public function actionGet($id){
        if ($this->redis->exists('user:'.$id)){
            $data = $this->change($id);
            return ['status' => 'success', 'response' => $data];
        }else{
            $this->redis->hset('user:'.$id,'car','none');
            $data = $this->change($id);
            return ['status' => 'success', 'response' => $data];
        }
    }

    public function actionUpdate(){
        $key = 'user:'.$this->post['user_id'];
        if ($this->redis->hexists($key, $this->post['item_id'])){
            $this->redis->hset($key,$this->post['item_id'],json_encode(['num'=>$this->post['num'],'total'=>$this->post['total']]));
            return ['status' => 'success', 'response' => 'success'];
        }else{
            return ['status' => 'error', 'response' => 'error'];
        }
    }

    public function change($id){
        $items = $this->redis->hgetall('user:'.$id);
        $data = [];
        for ($i=0;$i<count($items);$i=$i+2){
            $data[$items[$i]] = json_decode($items[$i+1]);
        }
        return $data;
    }
}