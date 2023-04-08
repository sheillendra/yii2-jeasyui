<?php

namespace sheillendra\jeasyui\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use sheillendra\jeasyui\models\LoginForm;

/**
 * Site controller
 */
class JeasyuiController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'forgot-password', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout', 'index', 'setting', 'setting-rbac',
                            'setting-user', 'profile'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index/index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        $req = Yii::$app->getRequest();
        if (!Yii::$app->user->isGuest) {
            if ($req->isAjax) {
                echo Json::encode(['redirect' => Yii::$app->getHomeUrl()]);
                return Yii::$app->end();
            }else{
                return $this->redirect(['/']);
            }
        }

        $model = new LoginForm();
        if ($model->load($req->post()) && $model->login()) {
            echo Json::encode([
                'redirect' => Yii::$app->getUser()->getReturnUrl(),
                'data' => [
                    'token' => $model->user->token,
                    'level' => $model->user->maxLevel,
                ]
            ]);
        } else {
            if ($model->hasErrors()) {
                echo Json::encode(['loginerror' => $model->getFirstErrors()]);
            } else {
                return $this->render('login/login', ['model' => $model]);
            }
        }
        return Yii::$app->end();
    }

    /**
     * Signup action.
     *
     * @return string
     */
    public function actionSignup() {
//        if (!Yii::$app->user->isGuest) {
//            echo Json::encode(['redirect' => Yii::$app->getHomeUrl()]);
//            return Yii::$app->end();
//        }
//
//        $model = new SignupForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            echo Json::encode(['redirect' => Yii::$app->getUser()->getReturnUrl()]);
//        } else {
//            if ($model->hasErrors()) {
//                echo Json::encode(['loginerror' => $model->getErrors()]);
//            } else {
//                return $this->render('signup', ['model' => $model]);
//            }
//        }
//        Yii::$app->end();
    }

    /**
     * Signup action.
     *
     * @return string
     */
    public function actionForgotPassword() {
//        if (!Yii::$app->user->isGuest) {
//            echo Json::encode(['redirect' => Yii::$app->getHomeUrl()]);
//            return Yii::$app->end();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            echo Json::encode(['redirect' => Yii::$app->getUser()->getReturnUrl()]);
//        } else {
//            if ($model->hasErrors()) {
//                echo Json::encode(['loginerror' => $model->getErrors()]);
//            } else {
//                return $this->render('signup', ['model' => $model]);
//            }
//        }
//        Yii::$app->end();
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        $this->enableCsrfValidation = false;
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    public function actionSetting() {
        return $this->render('setting/index');
    }

    public function actionSettingRbac() {
        return $this->render('setting-rbac/index');
    }

    public function actionSettingUser() {
        return $this->render('setting-user/index');
    }

    public function actionProfile() {
        return $this->render('profile/index');
    }

}
