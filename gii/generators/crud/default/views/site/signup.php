<?php
echo "<?php\n";
?>

use yii\helpers\Url;
use app\components\helpers\Regex;
use app\views\site\assets\SignupAsset;
use yii\web\View;
?>

<?= '<?php $this->beginPage();?>'."\n" ?>
<?= '<?php $this->head();?>'."\n" ?>
<?= '<?php $this->beginBody();?>'."\n" ?>
<div id="signup-dialog"></div>
<div id="signup-btn">
    <table width="100%">
        <tbody>
            <tr>
                <td width="50%"><div id="login-message"></div></td>
                <td>
                    <a id="signup-btn-ok"></a>
                    <a id="signup-btn-reset"></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?= '<?php $this->endBody();?>'."\n" ?>
<?= '<?php $this->endPage();?>'."\n" ?>
<?= "<?php\n"; ?>

$formContent = preg_replace(Regex::htmlMinified, ' ',$this->render('_signup-form',['model'=>$model],true));
$loginUrl =Url::toRoute(['/site/login'],true);
$signupUrl =Url::toRoute(['/site/signup'],true);
$forgotUrl =Url::toRoute(['/site/reset-password'],true);

$this->registerJs(
    <<< EOD
        yii.signup.content = '{$formContent}';
        yii.signup.url = '{$signupUrl}';
        yii.signup.loginurl = '{$loginUrl}';
        yii.signup.forgoturl = '{$forgotUrl}';
        yii.signup.init();
EOD
,View::POS_END);
        
SignupAsset::register($this);
