<?php
use yii\helpers\Url;

$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->title = 'Admin - ' . $this->title;

$this->params['loginDialogTitle'] = $this->title;
$this->params['loginDialogWidth'] = 350;
$this->params['loginDialogHeight'] = 250;
$this->params['textboxWidth'] = 230;

//accomodate login with email
$this->params['usernameSelector'] = '#loginform-username';

$this->params['loginUrl'] = Url::to(['/jeasyui/login'], true);
$this->params['signupUrl'] = Url::to(['/jeasyui/signup'], true);
$this->params['forgotUrl'] = Url::to(['/jeasyui/reset-password'], true);
