<?php
use yii\helpers\Url;
use sheillendra\jeasyui\assets\YiiEasyUIAsset;

$assetUrl = YiiEasyUIAsset::register($this);

$this->params['favico'] = $assetUrl->baseUrl . '/favicon.ico';
$this->title = $this->title;

$this->params['loginDialogTitle'] = $this->title;
$this->params['loginDialogWidth'] = 350;
$this->params['loginDialogHeight'] = 260;

//accomodate login with email
$this->params['usernameSelector'] = '#loginform-username';

$this->params['loginUrl'] = Url::to(['/jeasyui/login']);
$this->params['signupUrl'] = Url::to(['/jeasyui/signup']);
$this->params['forgotUrl'] = Url::to(['/jeasyui/reset-password']);
