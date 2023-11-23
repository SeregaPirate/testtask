<?php

namespace frontend\controllers;
use common\models\LoginForm;
use common\models\RegisterForm;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'login' => ['post'],
                    'register' => ['post'],
                ],
            ],

        ];
    }
    public function actionLogin(): array
    {
        $params = Yii::$app->request->getBodyParams();
        $email = $params['email'];
        $password = $params['password'];
        $model = new LoginForm();
        $model->email = $email;
        $model->password = $password;
        $accessToken = $model->login(false); // Вызываем метод login в модели LoginForm
        if ($accessToken !== false) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['accessToken' => $accessToken];
        }else {
            Yii::$app->response->statusCode = 401; // Устанавливаем статус код 401 для ошибки аутентификации
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => 'Invalid email or password',];
        }
    }
    public function actionRegister()
    {
        $model = new RegisterForm();
        $model->email = Yii::$app->request->post('email');
        $model->password = Yii::$app->request->post('password');
        $model->username = Yii::$app->request->post('username');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->register();

    }

}

