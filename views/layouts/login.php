<?php

use yii\helpers\Html;
use sheillendra\jeasyui\assets\JEasyUIAsset;

/* @var $this \yii\web\View */
/* @var $content string */

JEasyUIAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php $this->render('@app/views/layouts/_login-init_in_view') ?>
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
$this->endPage();
