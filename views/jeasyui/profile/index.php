<?php

/* @var $this \yii\web\View */

if (Yii::$app->request->isAjax) {
    $this->context->layout = '//blank';
    echo $this->renderAjax('_index');
} else {
    $this->params['selectedNav'] = 'nav-profile';
    $this->params['tabOptions'] = [
        'tabtitle' => 'Profile',
        'url' => \yii\helpers\Url::to(['/jeasyui/profile'], true),
        'icon' => 'icon-profile'
    ];
}