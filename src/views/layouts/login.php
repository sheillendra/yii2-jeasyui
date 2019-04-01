<?php

use yii\helpers\Html;
use sheillendra\jeasyui\assets\YiiEasyUILoginAsset;

/* @var $this \yii\web\View */
/* @var $content string */

YiiEasyUILoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php $this->render('@app/views/layouts/_init_login') ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?php echo $this->params['favico'] ?>">
        <?php $this->head() ?>
    </head>
    <body>
        <div id="error"></div>
        <?php $this->beginBody() ?>
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
        <?php $this->endBody()
        ?>
    </body>
</html>
<?php
$this->registerJs(<<<EOD
        yii.easyuiLogin.dialogTitle = '{$this->params['loginDialogTitle']}';
        yii.easyuiLogin.dialogWidth = {$this->params['loginDialogWidth']};
        yii.easyuiLogin.dialogHeight = {$this->params['loginDialogHeight']};
        yii.easyuiLogin.usernameSelector = '{$this->params['usernameSelector']}';
        yii.easyuiLogin.url = '{$this->params['loginUrl']}';
        yii.easyuiLogin.signupurl = '{$this->params['signupUrl']}';
        yii.easyuiLogin.forgoturl = '{$this->params['forgotUrl']}';
        yii.easyuiLogin.init();
EOD
);

$this->endPage();
