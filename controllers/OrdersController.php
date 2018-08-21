<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use Yii;
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
    private $redis;

    public function init()
    {
        $this->redis = Yii::$app->redis;
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
                    'delete' => ['POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate(){

    }

    public function actionUpdate(){
        $post = Yii::$app->request->post();
        if ($this->redis->exists($post['name'])){
//            for (){
//
//            }
        }else{

        }
    }

    public function actionDelete(){

    }

    public function actionFind($id){

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
