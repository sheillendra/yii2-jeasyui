<?php

/**
 * My standard login page
 * 
 */

use yii\helpers\Url;
use sheillendra\jeasyui\components\helpers\Regex;

/* @var $this \yii\web\View */

$this->title = 'Login';

$this->context->layout = '//login';
?>

<div id="login-dialog"></div>
<div id="login-btn">
    <table width="100%">
        <tbody>
            <tr>
                <td width="50%"><div id="login-message"></div></td>
                <td>
                    <a id="login-btn-ok"></a>
                    <a id="login-btn-reset"></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php
$formContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_login-form', ['model' => $model], true));
$header = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('_login-header', [], true));

$this->registerJs(<<<EOD
        yii.easyuiLogin.header = '{$header}';
        yii.easyuiLogin.content = '{$formContent}';
EOD
        , $this::POS_READY, 'login-view'
);
