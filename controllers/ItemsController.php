<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use app\models\ItemForm;
use app\models\UploadForm;
use Yii;
use app\models\Items;
use app\models\ItemsSearch;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'upload' => ['POST'],
                    'all'    => ['POST'],
                ],
            ],
        ];
    }


    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new ItemForm();
        if ($model->load($post,'') && $model->create()){
            return ['status' => 'success','response' => $model];
        }else{
            return ['status' => 'error','response' => $model];
        }
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if ($request->post('id')!=''){
            $model = Items::findOne($request->post('id'));
            if (isset($model)){
                $model->name = $request->post('name');
                $model->description = $request->post('description');
                $model->price = $request->post('price');
                $model->num = $request->post('num');
                $model->updated_at = date('Y-m-d H:i:s',time());
                $model->show_image = $request->post('show_image');
                $model->avatar = $request->post('avatar');
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
        $item = Items::findOne($id);
        if (isset($item)){
            if ($item->delete()){
                return ['status' => 'success','response' => 'success'];
            }else{
                return ['status' => 'error','response' => 'error'];
            }
        }else{
            return ['status' => 'error','response' => 'error'];
        }
    }

    public function actionFind($id)
    {
        $item = Items::findOne($id);
        if (isset($item)){
            return ['status' => 'success','response' => $item];
        }else{
            return ['status' => 'error','response' => 'error'];
        }
    }

    public function actionUpload(){
        $form = new UploadForm([
            'images' => UploadedFile::getInstanceByName('images')
        ]);

        if($form->upload()){
            return ['status' => 'success' , 'avatar' => Yii::$app->urlManager->baseUrl . $form->savePath];
        }else{
            return ['status' => 'error' , 'avatar' => null];
        }
    }


    public function actionAll(){
        $post = Yii::$app->request->post();
        $items = Items::find()->orderBy('num');
        $count = $items->count();
        if ($count!=0){
            $pagination = new Pagination(['totalCount' => $count]);
            $pagination->pageSize = $post['pageSize'];
            $pagination->page = $post['currentPage']-1;
            $data = $items->offset($pagination->offset)->limit($pagination->limit)->all();
            return ['status' => 'success', 'response' => $data];
        }else{
            return ['status' => 'error', 'response' => 'empty'];
        }

    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
