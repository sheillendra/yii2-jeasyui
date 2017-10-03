<?php

use sheillendra\jeasyui\assets\AppMobileAsset;
use yii\helpers\Html;
use sheillendra\helpers\Regex;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var $content string */

AppMobileAsset::register($this);
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
$northContent = preg_replace(Regex::htmlMinified, ' ', $this->render('@app/views/layouts/_north-content'));
$centerContent = '<div id="maintab"></div>';
$westContent = preg_replace(Regex::htmlMinified, ' ', $this->render('@app/views/layouts/_west-content'));

$this->params['selectedNav'] = isset($this->params['selectedNav']) ? $this->params['selectedNav'] :'nav-dashboard';

$navItem = include(Yii::$app->view->theme->applyTo(Yii::getAlias('@app/views/layouts/_nav-item.php')));

$modules = Yii::$app->getModules();
foreach( $modules as $module){
    if(is_array($module)){
        if( isset($module['menuNumber']) && method_exists($module['class'], 'setEasyuiNavigation')){
            $navItemFromModule = $module['class']::setEasyuiNavigation($module['menuNumber']);
            $navItem = array_merge($navItem,$navItemFromModule);
        }
    }elseif(is_object($module)){
        if(property_exists($module,'menuNumber') && method_exists($module, 'setEasyuiNavigation')){
            $navItemFromModule = $module::setEasyuiNavigation($module->menuNumber);
            $navItem = array_merge($navItem,$navItemFromModule);
        }
    }
}
ksort($navItem);
$navItem = Json::encode($navItem);

$myRoles = '{}';//Json::encode(Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id));

//$allRoles = Yii::$app->authManager->getRoles();
//$arrRoles = [];
//foreach($allRoles as $k=>$v){
//    $stdClass = new stdClass();
//    $stdClass->name = $v->name;
//    $arrRoles[] = $stdClass;
//}
$roles = '{}';//Json::encode($arrRoles);

$errors = isset($this->params['error'])?"yii.easyui.errors = ". Json::encode($this->params['error'] ) .";":'';
        
$this->registerJs(<<<EOD
    yii.easyui.username = '{$username}';
    yii.easyui.logoutUrl = '{$logoutUrl}';
    yii.easyui.profileUrl = '{$profileUrl}';
    yii.easyui.getReferenceUrl = '{$getReferenceUrl}';
    yii.easyui.northContent = '{$northContent}';
    yii.easyui.centerContent = '{$centerContent}';
    yii.easyui.westContent = '{$westContent}';
    yii.easyui.navItem = {$navItem};
    yii.easyui.selectedNav = '{$this->params['selectedNav']}';
    yii.easyui.myRoles = {$myRoles};
    yii.easyui.reference.roles = {$roles};
    {$errors}
    yii.easyui.showMainMask();
    yii.easyui.init();
EOD
);?>

<?php $this->endPage(); ?>
