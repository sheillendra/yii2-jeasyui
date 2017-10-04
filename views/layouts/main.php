<?php

use yii\helpers\Html;
use yii\helpers\Json;
use sheillendra\jeasyui\components\helpers\Regex;
use sheillendra\jeasyui\assets\YiiEasyUIAsset;

/* @var $this \yii\web\View */
/* @var $content string */

YiiEasyUIAsset::register($this);
?>
<?php $this->render('@app/views/layouts/_init_logged') ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Loading...</title>
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
$westContent = $this->params['sidebarPlugin'] === 'tree' ? '<ul id="navigation"></ul>' : '<div id="navigation"></div>';
$navItem = include(Yii::$app->view->theme->applyTo(Yii::getAlias('@app/views/layouts/_nav-item.php')));
ksort($navItem);
$navItemJson = Json::encode($navItem);
$errors = isset($this->params['error']) ? "yii.easyui.errors = " . Json::encode($this->params['error']) . ";" : '';

$this->params['selectedNav'] = isset($this->params['selectedNav']) ? $this->params['selectedNav'] : 'nav-dashboard';

$tabOptions = isset($this->params['tabOptions'])? Json::encode($this->params['tabOptions']) : 0;

$northUserMenu = isset($this->params['northUserMenu'])? Json::encode($this->params['northUserMenu']) : 0;

$this->registerJs(<<<EOD
    yii.easyui.username = '{$this->params['userName']}';
    yii.easyui.getReferenceUrl = '{$this->params['getReferenceUrl']}';
    yii.easyui.northContent = '{$northContent}';
    yii.easyui.centerContent = '{$centerContent}';
    yii.easyui.westContent = '{$westContent}';
    yii.easyui.navItem = {$navItemJson};
    yii.easyui.selectedNav = '{$this->params['selectedNav']}';
    yii.easyui.sidebarPlugin = '{$this->params['sidebarPlugin']}';
    {$errors}
    yii.easyui.tabOptions = {$tabOptions};
    yii.easyui.showMainMask();
    yii.easyui.northUserMenu = {$northUserMenu};
    yii.easyui.init();
EOD
);
?>

<?php
$this->endPage();
