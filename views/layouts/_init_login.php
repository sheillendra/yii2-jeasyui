<?php
use yii\helpers\Url;

$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->title = $this->title;

$this->params['loginDialogTitle'] = $this->title;
$this->params['loginDialogWidth'] = 350;
$this->params['loginDialogHeight'] = 260;

//accomodate login with email
$this->params['usernameSelector'] = '#loginform-username';

$this->params['loginUrl'] = Url::to(['/jeasyui/login']);
$this->params['signupUrl'] = Url::to(['/jeasyui/signup']);
$this->params['forgotUrl'] = Url::to(['/jeasyui/reset-password']);
