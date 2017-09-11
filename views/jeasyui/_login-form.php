<?php

use yii\helpers\Html;

/* @var $model backend\modules\user\models\LoginForm*/
?>
<?php echo Html::beginForm(['/user/login'], 'post', ['id' => 'login-form']) ?>
<table width="100%">
    <tbody>
        <tr>
            <td colspan="3"><i>Please enter your username and password</i></td>
        </tr>
        <tr>
            <td width="80px"><?php echo Html::activeLabel($model, 'username') ?></td>
            <td>:</td>
            <td><?php echo Html::activeTextInput($model, 'username') ?></td>
        </tr>
        <tr>
            <td><?php echo Html::activeLabel($model, 'password') ?></td>
            <td>:</td>
            <td><?php echo Html::activeTextInput($model, 'password') ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><?php echo Html::activeCheckbox($model, 'rememberMe') ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <a id="login-forgot-btn" href="javascript:void(0);">Forgot Password</a>, or 
                <a id="login-signup-btn" href="javascript:void(0);">SignUp</a></td>
        </tr>
    </tbody>
</table>
<?php echo Html::endForm() ?>
