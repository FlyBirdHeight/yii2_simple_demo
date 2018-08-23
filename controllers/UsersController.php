<?php

namespace app\controllers;

use app\components\ReturnBehavior;
use app\models\RegisterForm;
use app\models\RewritePasswordForm;
use app\models\UserLoginForm;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    public $enableCsrfValidation = false;

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
                    'login' => ['POST'],
                    'register' => ['POST'],
                    'logout' => ['POST'],
                    'rewrite-password' => ['POST']
                ],
            ],
        ];
    }

    public function actionRegister(){
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post(),'') && $model->register()){
            return ['status' => 'success','response' => $model];
        }else{
            return ['status' => 'error','response' => $model];
        }
    }


    public function actionLogin(){
        $post = Yii::$app->request->post();
        $login = new UserLoginForm();
        if ($login->load($post,'') && $login->validate()){
            $user = $login->login();
//            Yii::$app->user->login($user);
            if ($this->redis->hexists('online','user:'.$user->id)){
                return ['status' => 'error', 'response' => 'online'];
            }else{
                $this->redis->hset('online','user:'.$user->id,$user->email);
                return ['status' => 'success', 'response' => $user];
            }
        }else{
            return ['status' => 'error', 'response' => 'error'];
        }

    }

    public function actionLogout()
    {
        $post = Yii::$app->request->post();
//        Yii::$app->user->logout();
        if ($this->redis->hexists('online','user:'.$post['user_id'])){
            $this->redis->hdel('online','user:'.$post['user_id']);
            return ['status' => 'success','response' => '退出成功'];
        }else{
            return ['status' => 'error','response' => '未登陆'];
        }
    }

    public function actionRewritePassword()
    {
        $post = Yii::$app->request->post();
        $model = new RewritePasswordForm();
        if ($model->load($post,'') && $model->rewritePassword()){
            return ['status' => 'success', 'response' => 'success'];
        }else{
            var_dump($model->getErrors());
            return ['status' => 'error', 'response' => $model];
        }
    }

    public function actionUser($id){
        $model = Users::findOne($id);
        if ($model){
            return ['status' => 'success', 'response' => $model];
        }else{
            return ['status' => 'error', 'response' => 'empty'];
        }
    }

    public function actionAddress($id){
        $user = Users::findOne($id);
        if (!$user){
            return ['status' => 'error', 'response' => 'error'];
        }else{
            $address = $user->addresses;
            return ['status' => 'success', 'response' => $address];
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        return null;
    }
}
