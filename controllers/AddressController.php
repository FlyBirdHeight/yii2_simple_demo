<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use app\models\CreateAddressForm;
use app\models\Items;
use Yii;
use app\models\Address;
use app\models\AddressSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends Controller
{
    public $enableCsrfValidation = false;
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
//                        'actions' => ['delete','create','update','find'],
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
                    'find'   => ['GET'],
                ],
            ],
        ];
    }


    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new CreateAddressForm();
        if ($model->load($post,'') && $model->create()){
            return ['status' => 'success','response' => $model];
        }else{
            var_dump($model->getErrors());
            return ['status' => 'error','response' => $model];
        }
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if ($request->post('id')!=''){
            $model = Address::findOne($request->post('id'));
            if (isset($model)){
                $model->code = $request->post('code');
                $model->consignee = $request->post('consignee');
                $model->phone = $request->post('phone');
                $model->residence = $request->post('residence');
                $model->updated_at = date('Y-m-d H:i:s',time());
                if ($model->save()){
                    return ['status' => 'success','response' => $model];
                }else{
                    return ['status' => 'error','response' => 'error'];
                }
            }else{
                return ['status' => 'error','response' => 'error'];
            }

        }else{
            return ['status' => 'error','response' => 'error'];
        }

    }

    public function actionDelete($id)
    {
        $model = Address::findOne($id);
        if (isset($model)){
            if ($model->delete()){
                return ['status' => 'success','response' => 'success'];
            }else{
                return ['status' => 'error','response' => 'error'];
            }
        }else{
            return ['status' => 'error','response' => 'error'];
        }

    }

    public function actionFind($id){
        $model = $this->findModel($id);
        if (isset($model)){
            return ['status' => 'success','response' => $model];
        }else{
            return ['status' => 'error','response' => 'error'];
        }
    }



    /**
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Address::findOne($id)) !== null) {
            return $model;
        }
        return null;
    }
}
