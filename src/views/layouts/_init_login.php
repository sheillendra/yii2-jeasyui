<?php
use yii\helpers\Url;

$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->title = $this->title;

$this->params['loginDialogTitle'] = $this->title;
$this->params['loginDialogWidth'] = 350;
$this->params['loginDialogHeight'] = 290;

//accomodate login with email
$this->params['usernameSelector'] = '#loginform-username';

$this->params['loginUrl'] = Url::to(['/site/login']);
$this->params['signupUrl'] = Url::to(['/site/signup']);
$this->params['forgotUrl'] = Url::to(['/site/reset-password']);
