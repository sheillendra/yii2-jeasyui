<?php
echo "<?php\n";
?>

use app\views\site\assets\LoginAsset;
use yii\helpers\Url;
use app\components\helpers\Regex;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = 'Login';

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

<?= "<?php\n" ?>

$formContent = preg_replace(Regex::htmlMinified, ' ',$this->render('_login-form',['model'=>$model],true));
$loginUrl =Url::toRoute(['/site/login'],true);
$signupUrl =Url::toRoute(['/site/signup'],true);
$forgotUrl =Url::toRoute(['/site/reset-password'],true);

$this->registerJs(
    <<< EOD
        yii.login.content = '{$formContent}';
        yii.login.url = '{$loginUrl}';
        yii.login.signupurl = '{$signupUrl}';
        yii.login.forgoturl = '{$forgotUrl}';
        yii.login.init();
EOD
);

LoginAsset::register($this);