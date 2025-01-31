<?php

/**
 * My standard login page
 * 
 */

use sheillendra\jeasyui\components\helpers\Regex;

/* @var $this \yii\web\View */

$this->title = 'Login';
$this->context->layout = '//login';

$formContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_login-form', ['model' => $model], true));
$header = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_login-header', [], true));

$this->registerJs(
    <<<EOD
        yii.easyuiLogin.header = '{$header}';
        yii.easyuiLogin.content = '{$formContent}';
EOD,
    $this::POS_READY,
    'login-view'
);