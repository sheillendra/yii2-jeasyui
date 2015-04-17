<?php
echo "<?php\n";
?>
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= "<?=Html::beginForm('/user/login','',['id'=>'login-form'])?>\n" ?>
    <table width="100%">
        <tbody>
            <tr>
                <td colspan="3"><i>Please enter your username and password</i></td>
            </tr>
            <tr>
                <?= '<td width="80px"><?= Html::activeLabel($model, \'username\')?></td>'."\n" ?>
                <td>:</td>
                <?= '<td><?= Html::activeTextInput($model, \'username\')?></td>'."\n" ?>
            </tr>
            <tr>
                <?= '<td><?= Html::activeLabel($model, \'password\')?></td>'."\n" ?>
                <td>:</td>
                <?= '<td><?= Html::activePasswordInput($model, \'password\')?></td>'."\n" ?>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <?= '<td><?= Html::activeCheckbox($model, \'rememberMe\') ?></td>'."\n" ?>
            </tr>
            <tr>
                <td colspan="3">
                    <a id="login-forgot-btn" href="javascript:void(0);">Forgot Password</a>, or 
                    <a id="login-signup-btn" href="javascript:void(0);">SignUp</a></td>
            </tr>
        </tbody>
    </table>
<?= '<?=Html::endForm()?>'."\n" ?>