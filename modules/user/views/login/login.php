<?php
/**
 * My standard login page, to use this view, you must customize controller like this :
 * 
 * ```
 * 
 * ```
 */

//use sheillendra\jeasyui\assets\LoginAsset;
use backend\themes\jeasyui\assets\LoginAsset;
use yii\helpers\Url;
use sheillendra\helpers\Regex;

/* @var $this \yii\web\View */

LoginAsset::register($this);

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
print_r($this->params);
$formContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/modules/user/views/login/_login-form', ['model' => $model], true));
$header = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/modules/user/views/login/_login-header', [], true));
$loginUrl = Url::to(['/user/login/jeasyui-login']);
$signupUrl = Url::to(['/user/signup']);
$forgotUrl = Url::to(['/user/reset-password']);

$this->registerJs(<<<EOD
        yii.login.header = '{$header}';
        yii.login.content = '{$formContent}';
        yii.login.url = '{$loginUrl}';
        yii.login.signupurl = '{$signupUrl}';
        yii.login.forgoturl = '{$forgotUrl}';
        yii.login.init();
EOD
);
