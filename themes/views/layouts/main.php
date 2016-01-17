<?php

use sheillendra\jeasyui\themes\assets\AppAsset;
use yii\helpers\Html;
use sheillendra\helpers\Regex;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
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
$username = Yii::$app->user->identity->username;
$logoutUrl = Url::to(['/user/logout'],true);
$profileUrl  = Url::to(['/user/profile'],true);
$getReferenceUrl = Url::to(['/reference/get'],true);
$northContent = preg_replace(Regex::htmlMinified, ' ', $this->render('_north-content'));
$centerContent = '<div id="maintab"></div>';
$westContent = preg_replace(Regex::htmlMinified, ' ', $this->render('_west-content'));

$this->params['selectedNav'] = isset($this->params['selectedNav']) ? $this->params['selectedNav'] :'nav-dashboard';

require(__DIR__ . '/_nav-item.php');

$modules = Yii::$app->getModules();
foreach( $modules as $module){
    if(is_array($module)){
        if(method_exists($module['class'], 'setEasyuiNavigation')){
            $navItemFromModule = $module['class']::setEasyuiNavigation();
            $navItem = array_merge($navItem,$navItemFromModule);
        }
    }elseif(is_object($module)){
        if(method_exists($module, 'setEasyuiNavigation')){
            $navItemFromModule = $module::setEasyuiNavigation();
            $navItem = array_merge($navItem,$navItemFromModule);
        }
    }
}

$navItemJson = Json::encode($navItem);

$myRoles = Json::encode(Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id));

$allRoles = Yii::$app->authManager->getRoles();
$arrRoles = [];
foreach($allRoles as $k=>$v){
    $stdClass = new stdClass();
    $stdClass->name = $v->name;
    $arrRoles[] = $stdClass;
}
$roles = Json::encode($arrRoles);

$errors = isset($this->params['error'])?"yii.app.errors = '". implode(', ', $this->params['error'] ) ."';":'';
        
$this->registerJs(<<<EOD
    yii.app.username = '{$username}';
    yii.app.logoutUrl = '{$logoutUrl}';
    yii.app.profileUrl = '{$profileUrl}';
    yii.app.getReferenceUrl = '{$getReferenceUrl}';
    yii.app.northContent = '{$northContent}';
    yii.app.centerContent = '{$centerContent}';
    yii.app.westContent = '{$westContent}';
    yii.app.navItem = {$navItemJson};
    yii.app.selectedNav = '{$this->params['selectedNav']}';
    yii.app.myRoles = {$myRoles};
    yii.app.reference.roles = {$roles};
    {$errors}
    yii.app.showMainMask();
    yii.app.init();
EOD
);?>

<?php $this->endPage(); ?>
