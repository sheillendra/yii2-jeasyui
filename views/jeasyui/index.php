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

$this->title = 'Dashboard';
$this->params['selectedNavAccordion'] = 'dashboard';
$this->params['selectedNav'] = 'nav-dashboard';