<?php

/* @var $this \yii\web\View */

if (Yii::$app->request->isAjax) {
    $this->context->layout = '//blank';
    echo $this->renderAjax('_index');
} else {
    $this->params['selectedNav'] = 'nav-profile';
    $this->params['tabOptions'] = [
        'title' => 'Profile',
        'href' => \yii\helpers\Url::to(['/jeasyui/profile'], true),
        'iconCls' => 'icon-profile'
    ];
}