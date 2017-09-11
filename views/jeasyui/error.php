<?php
/* @var $this \yii\web\View */

if(Yii::$app->user->isGuest){
    $this->context->layout = '//login';
}

