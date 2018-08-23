<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use app\events\AddOrderCount;
use app\events\CreateOrderItem;
use app\models\Address;
use app\models\Items;
use Yii;
use yii\filters\AccessControl;
use app\models\Orders;
use app\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    public $enableCsrfValidation = false;

    private $redis;

    const EVENT_ADD_ORDER_COUNT = 'add_order_count';


    public function init()
    {
        $this->redis = Yii::$app->redis;
        $this->on(self::EVENT_ADD_ORDER_COUNT,['app\models\Users','addOrderCount']);
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
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['delete','create'],
//                        'except' => ['find'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate(){
        $post = Yii::$app->request->post();
        $key = 'user:'.$post['user_id'];
        $order_id = null;
        $event = new AddOrderCount();
        if ($this->redis->exists($key)){
            if ($this->redis->hexists($key,'car')){
                return ['status'=> 'error', 'response' => 'none'];
            }else{
                if (Address::findOne($post['address_id'])!=null){
                    $data = $this->change($post['user_id']);
                    $count = 0;
                    $total = 0;
                    foreach ($data as $key=>$value){
                        $count+=$value->num;
                        $total+=$value->total;
                        try{
                            $tb = Yii::$app->db->beginTransaction();
                            $sql = "select * from items where id = :id for update";
                            $items = Yii::$app->db->createCommand($sql,[':id'=>$key])->queryOne();
                            if($items['num'] >= $value->num){
                                sleep(0.1);
                                $items = Items::findOne(['id' => $key]);
                                $items->num -= $value->num;
                                $items->updated_at = date('Y-m-d H:i:s');
                                if($items->save()){
                                    if ($order_id!=null){
                                        $order = Orders::findOne($order_id);
                                        $order->item_count = $count;
                                        $order->total = $total;
                                        $order->save();
                                        $tb->commit();
                                    }else{
                                        $order = new Orders();
                                        $order->order_code =  date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                                        $order->user_id  =  $post['user_id'];
                                        $order->item_count = $count;
                                        $order->total = $total;
                                        $order->address_id = $post['address_id'];
                                        if($order->save()){
                                            $order_id = $order->id;
                                            $order->link('items',Items::findOne($key),['num' => $value->num, 'total' => $value->total]);
                                            $event->user_id = $post['user_id'];
                                            $this->trigger(self::EVENT_ADD_ORDER_COUNT,$event);
                                            $tb->commit();
                                        }
                                    }
                                }
                            }
                            else{
                                return ['status' => 'error', 'response' => 'Some mistakes '];
                            }
                        }catch (\Exception $e){
                            return ['status' => 'error', 'response' => 'error'];
                        }
                    }
                    Yii::$app->redis->del('user:'.$post['user_id']);
                    return ['status'=>'success', 'response'=>Orders::findOne($order_id)];
                }else{
                    return ['status' => 'error', 'response' => 'address none'];
                }
            }
        }else{
            return ['status' => 'error', 'response' => 'shop car not exists'];
        }

    }

    public function actionDelete(){
        $post = Yii::$app->request->post();
        $order = Orders::findOne($post['order_id']);
        if (Yii::$app->redis->hexists('online','user'.$post['user_id']) && $order!=null){
            $order->delete();
            return ['status' => 'success', 'response' => 'Delete success'];
        }else{
            return ['status' => 'error', 'response' => 'Some mistakes'];
        }
    }

    public function actionFind($id){
        $data = Orders::find()->where(['id'=>$id])->with(['items','users','address'])->asArray()->all();
        if ($data!=null){
            return ['status' => 'success', 'response' => $data];
        }else{
            return ['status' => 'error', 'response' => 'none'];
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

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
