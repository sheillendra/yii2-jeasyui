<?php

use yii\helpers\Html;
use sheillendra\jeasyui\assets\YiiEasyUIMobileLoginAsset;

/* @var $this \yii\web\View */
/* @var $content string */

YiiEasyUIMobileLoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="easyui-navpanel">
            <header>
                <div class="m-toolbar">
                    <span class="m-title">Login to System</span>
                </div>
            </header>
            <div style="margin:20px auto;width:100px;height:100px;border-radius:100px;overflow:hidden">
                <img src="../images/login1.jpg" style="margin:0;width:100%;height:100%;">
            </div>
            <?php echo $content?>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
