<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use app\models\ItemForm;
use app\models\UploadForm;
use Yii;
use app\models\Items;
use app\models\ItemsSearch;
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
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->images = UploadedFile::getInstance($model,'images');

            if ($model->images && $model->validate()) {
                $path = \Yii::$app->aliases->getUpload() . $model->images->baseName . '.' . $model->images->extension;
                $model->images->saveAs($path);
                return ['status' => 'success', 'response' => $path];
            }
            return ['status' => 'error', 'response' => 'error'];
        }
        return ['status' => 'error', 'response' => 'error'];

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
