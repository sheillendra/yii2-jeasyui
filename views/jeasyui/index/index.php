<?php

$this->title = 'Dashboard';

if (Yii::$app->request->isAjax) {
    $this->context->layout = '//blank';
    echo $this->renderAjax('_index');
} else {
    $this->params['selectedNav'] = 'nav-dashboard';
}