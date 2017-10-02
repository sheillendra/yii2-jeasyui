<?php

use yii\helpers\Html;
use yii\helpers\Json;
use sheillendra\jeasyui\components\helpers\Regex;
use sheillendra\jeasyui\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->render('@app/views/layouts/_init_logged') ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <div class="main-mask overlay" style="display: none"></div>
        <div class="main-mask loader" style="display: none">Processing, please wait ...</div>
        <div id="global-error"></div>
        <?php $this->beginBody() ?>
        <?php $this->endBody() ?>
    </body>
</html>

<?php
$northContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_north-content'));
$centerContent = '<div id="maintab"></div>';
$westContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_west-content'));
$navItem = include(Yii::$app->view->theme->applyTo(Yii::getAlias('@app/views/layouts/_nav-item.php')));
ksort($navItem);
$navItemJson = Json::encode($navItem);
$errors = isset($this->params['error']) ? "yii.app.errors = " . Json::encode($this->params['error']) . ";" : '';

$this->params['selectedNav'] = isset($this->params['selectedNav']) ? $this->params['selectedNav'] : 'nav-dashboard';

$this->registerJs(<<<EOD
    yii.app.username = '{$this->params['userName']}';
    yii.app.logoutUrl = '{$this->params['logoutUrl']}';
    yii.app.profileUrl = '{$this->params['profileUrl']}';
    yii.app.getReferenceUrl = '{$this->params['getReferenceUrl']}';
    yii.app.northContent = '{$northContent}';
    yii.app.centerContent = '{$centerContent}';
    yii.app.westContent = '{$westContent}';
    yii.app.navItem = {$navItemJson};
    yii.app.selectedNav = '{$this->params['selectedNav']}';
    {$errors}
    yii.app.showMainMask();
    yii.app.init();
EOD
);
?>

<?php
$this->endPage();
