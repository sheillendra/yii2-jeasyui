<?php

/**
 * add ajax handler in your controller method / action :
 * ```
 * class SiteController extends Controller
 * {
 *     public function actionIndex()
 *     {
 *        if (Yii::$app->request->isAjax) {
 *             echo $this->renderAjax('_index');
 *             return Yii::$app->end();
 *         }
 *         return $this->render('index');
 *     }
 * }
 * ```
 */
/* @var $this \yii\web\View */

$this->params['selectedNav'] = 'nav-profile';
$this->params['tabOptions'] = [
    'tabtitle' => 'Profile',
    'url' => \yii\helpers\Url::to(['/jeasyui/profile'], true),
    'icon' => 'icon-profile'
];
