<?php

namespace sheillendra\jeasyui\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use sheillendra\jeasyui\models\LoginForm;
use sheillendra\jeasyui\models\ChangePasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
                            'setting-user', 'profile', 'change-password'
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
    public function actions()
    {
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
    public function actionIndex()
    {
        return $this->render('index/index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $req = Yii::$app->getRequest();
        if (!Yii::$app->user->isGuest) {
            if ($req->isAjax) {
                return Json::encode(['redirect' => Yii::$app->getHomeUrl()]);
            } else {
                return $this->redirect(['/']);
            }
        }

        $model = new LoginForm();
        if ($model->load($req->post()) && $model->login()) {
            return Json::encode([
                'redirect' => Yii::$app->getUser()->getReturnUrl(),
                'data' => [
                    'token' => $model->user->token,
                    'level' => $model->user->authMaxLevel,
                ]
            ]);
        } else {
            if ($model->hasErrors()) {
                return Json::encode(['loginerror' => $model->getFirstErrors()]);
            } else {
                return $this->render('login', ['model' => $model]);
            }
        }
    }

    /**
     * Signup action.
     *
     * @return string
     */
    public function actionSignup()
    {
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
    public function actionForgotPassword()
    {
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
    public function actionLogout()
    {
        $this->enableCsrfValidation = false;
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    public function actionSetting()
    {
        return $this->render('setting/index');
    }

    public function actionSettingRbac()
    {
        return $this->render('setting-rbac/index');
    }

    public function actionSettingUser()
    {
        return $this->render('setting-user/index');
    }

    public function actionProfile()
    {

        return $this->render('profile/index');
    }

    public function actionChangePassword()
    {
        $result = [
            'success' => false,
            'message' => '',
        ];
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->change()) {
            Yii::$app->user->logout();
            $result['success'] = true;
        } else {
            $result['message'] = implode(', ', $model->getFirstErrors());
        }
        echo Json::encode($result);
        return Yii::$app->end();
    }
}
