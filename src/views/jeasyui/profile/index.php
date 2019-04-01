<?php

/* @var $this \yii\web\View */

use sheillendra\jeasyui\components\helpers\Regex;

if (Yii::$app->request->isAjax) {
    $this->context->layout = '//ajax';
    echo $this->renderAjax('_index');
} else {
    $this->params['selectedNav'] = 'nav-profile';
    $this->params['tabOptions'] = [
        'title' => 'Profile',
        'iconCls' => 'icon-profile',
        'content' => preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_index'))
    ];
}