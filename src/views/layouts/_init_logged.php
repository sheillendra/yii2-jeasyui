<?php

use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Admin - ' . $this->title;

//required params
$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->params['userName'] = Yii::$app->user->identity->username;
$this->params['homeUrl'] = Url::to(['/']);
$this->params['logoutUrl'] = Url::to(['/jeasyui/logout']);
$this->params['getReferenceUrl'] = Url::to(['/jeasyui/reference']);
$this->params['westTitle'] = 'Menu Utama';
$this->params['westIcon'] = 'icon-compass';
$this->params['sidebarPlugin'] = 'tree'; //tree or accordion

$this->render('@app/views/layouts/_init_north-user-menu');
