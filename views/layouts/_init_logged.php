<?php

use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Admin - ' . $this->title;

//required params
$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->params['userName'] = Yii::$app->user->identity->username;
$this->params['logoutUrl'] = Url::to(['/jeasyui/logout']);
$this->params['getReferenceUrl'] = Url::to(['/jeasyui/reference']);
$this->params['westTitle'] = 'Navigation';
$this->params['westIcon'] = 'icon-compass';
$this->params['sidebarPlugin'] = 'tree'; //tree or accordion
$this->params['northUserMenu'] = [];

//custom from here
$profileUrl = Url::to(['/jeasyui/profile']);

$this->params['northUserMenu'][] = [
    'text' => 'Profile',
    'iconCls' => 'icon-profile',
    'onclick' => new JsExpression(<<<JS
        function (e) {
            yii.easyui.createTab({
                title: 'Profile',
                href: '{$profileUrl}',
                iconCls: 'icon-profile'
            }, 'nav-profile');
            e.stopPropagation();
        }
JS
    )
];

$this->params['northUserMenu'][] = ['separator' => 1];
$this->params['northUserMenu'][] = [
    'text' => 'Theme',
    'iconCls' => 'icon-profile',
];

$templateThemeOnClick = <<<JS
    function (e) {
        yii.easyui.cookie.delete('jeasyui-theme');
        yii.easyui.cookie.set('jeasyui-theme', '{thema}');
        window.location.reload(false);
        e.stopPropagation();
    }
JS;
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Default',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'default']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Black',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'black']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Bootstrap',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'bootstrap']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Gray',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'gray']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Material',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'separator' => 1
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro Blue',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro-blue']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro Green',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro-green']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro Gray',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro-gray']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro Orange',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro-orange']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro Red',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro-red']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'separator' => 1
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'UI Cupertino',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'ui-cupertino']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'UI Dark Hive',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'ui-dark-hive']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'UI Pepper Grinder',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'ui-pepper-grinder']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'UI Sunny',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'ui-sunny']))
];
$this->params['northUserMenu'][] = ['separator' => 1];

$this->params['northUserMenu'][] = [
    'text' => 'Logout',
    'iconCls' => 'icon-door-out',
    'onclick' => new JsExpression(<<<JS
        function (e) {
            yii.handleAction($('<a href="{$this->params['logoutUrl']}" data-method="post"></a>'));
            e.stopPropagation();
        }
JS
    )
];
