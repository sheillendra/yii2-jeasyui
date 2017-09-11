<?php

use yii\helpers\Html;
use sheillendra\jeasyui\assets\JEasyUIAsset;

/* @var $this \yii\web\View */
/* @var $content string */

JEasyUIAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php $this->render('@app/views/layouts/_init_login') ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <div id="error"></div>
        <?php $this->beginBody() ?>
        <?php echo $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php
$this->registerJs(<<<EOD
        yii.login.dialogTitle = '{$this->params['loginDialogTitle']}';
        yii.login.dialogWidth = {$this->params['loginDialogWidth']};
        yii.login.dialogHeight = {$this->params['loginDialogHeight']};
        yii.login.textboxWidth = {$this->params['textboxWidth']};
        yii.login.usernameSelector = '{$this->params['usernameSelector']}';
        yii.login.url = '{$this->params['loginUrl']}';
        yii.login.signupurl = '{$this->params['signupUrl']}';
        yii.login.forgoturl = '{$this->params['forgotUrl']}';
        yii.login.init();
EOD
);

$this->endPage();
