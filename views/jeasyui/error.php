<?php
/* @var $this \yii\web\View */

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

if (Yii::$app->user->isGuest) {
    $this->context->layout = '//login';
}

$this->params['errorName'] = $name;
$this->params['errorMessage'] = $message;
