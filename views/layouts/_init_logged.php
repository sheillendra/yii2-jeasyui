<?php
use yii\helpers\Url;

$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->title = 'Admin - ' . $this->title;

$this->params['userName'] = Yii::$app->user->identity->username;
$this->params['logoutUrl'] = Url::to(['/jeasyui/logout'], true);
$this->params['profileUrl'] = Url::to(['/jeasyui/profile'], true);
$this->params['getReferenceUrl'] = Url::to(['/jeasyui/reference'], true);
$this->params['sidebarPlugin'] = 'tree'; // tree or accordion
