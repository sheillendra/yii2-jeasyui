<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use sheillendra\jeasyui\components\helpers\Regex;
use sheillendra\jeasyui\components\helpers\ArrayHelper;
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <?= Html::csrfMetaTags() ?>
    <title>Loading...</title>
    <?php $this->head() ?>
</head>

<body>
    <div id="main-mask-overlay" class="main-mask overlay"></div>
    <div id="main-mask" class="panel-body panel-loading main-mask loader"><?php echo Yii::t('app', 'Please wait ...'); ?></div>
    <div id="global-error"></div>
    <div id="notification-drawer" style="width:250px"></div>
    <?php $this->beginBody() ?>
    <?php $this->endBody() ?>
</body>

</html>

<?php
$navbarContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_navbar-content'));
$leftRailContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_left-rail-content'));
$northContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_north-content'));
$southContent = preg_replace(Regex::HTML_MINIFIED, ' ', $this->render('@app/views/layouts/_south-content'));
$centerContent = '<div id="maintab"></div>';
$westContent = $this->params['sidebarPlugin'] === 'tree' ? '<ul id="navigation"></ul>' : '<div id="navigation"></div>';

$westTitle = isset($this->params['westTitle']) ? $this->params['westTitle'] : '';
$westIcon = isset($this->params['westIcon']) ? $this->params['westIcon'] : '';

$this->render('@app/views/layouts/_nav-item.php');
$this->render('@app/views/layouts/_nav-item-map.php');

$errors = '';
if (isset($this->params['errorName'])) {
    $errorNav = ArrayHelper::getArrayByKeyValue('id', 'url', Url::to(), $this->params['navItem']);
    if ($errorNav) {
        $this->params['selectedNav'] = $errorNav['id'];
        $this->params['tabOptions'] = [
            'title' => $errorNav['text'],
            'iconCls' => $errorNav['iconCls'],
            'content' => $this->params['errorName'] . ': ' . $this->params['errorMessage'],
            'data' => [
                'url' => $errorNav['attributes']['url']
            ]
        ];
    } else {
        if (isset($this->params['tabOptions'])) {
            $this->params['tabOptions']['content'] = $this->params['errorName'] . ': ' . $this->params['errorMessage'];
        } else {
            $this->params['selectedNav'] = $this->params['defaultSelectedNav'];
            $errors = <<<JS
                yii.easyui.errorName = '{$this->params['errorName']}';
                yii.easyui.errorMessage = '{$this->params['errorMessage']}';
JS;
        }
    }
} else {
    $this->params['selectedNav'] = isset($this->params['selectedNav']) ? $this->params['selectedNav'] : $this->params['defaultSelectedNav'];
}

$navItemJson = Json::encode(array_values($this->params['navItem']));

$tabOptions = isset($this->params['tabOptions']) ? Json::encode($this->params['tabOptions']) : 0;
$this->registerJs(
    <<<JS
    yii.easyui.locale = 'en-PG';
    yii.easyui.currency = 'PGK';
    yii.easyui.username = '{$this->params['userName']}';
    yii.easyui.getReferenceUrl = '{$this->params['getReferenceUrl']}';
    yii.easyui.homeUrl = '{$this->params['homeUrl']}';
    yii.easyui.logoutUrl = '{$this->params['logoutUrl']}';
    yii.easyui.navbarContent = '{$navbarContent}';
    yii.easyui.leftRailContent = '{$leftRailContent}';
    yii.easyui.northContent = '{$northContent}';
    yii.easyui.southContent = '{$southContent}';
    yii.easyui.centerContent = '{$centerContent}';
    yii.easyui.westContent = '{$westContent}';
    yii.easyui.westTitle = '{$westTitle}';
    yii.easyui.westIcon = '{$westIcon}';
    yii.easyui.navItem = {$navItemJson};
    yii.easyui.selectedNav = '{$this->params['selectedNav']}';
    yii.easyui.sidebarPlugin = '{$this->params['sidebarPlugin']}';
    {$errors}
    yii.easyui.tabOptions = {$tabOptions};

    yii.easyui.setHost('base', '{$this->params['baseUrl']}');
    yii.easyui.setHost('app', '{$this->params['appUrl']}');
    yii.easyui.init();
JS
);

$this->endPage();
