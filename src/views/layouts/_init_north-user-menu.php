<?php

use yii\helpers\Url;
use yii\web\JsExpression;

$this->params['northUserMenu'] = [];

//custom from here
$profileUrl = Url::to(['/jeasyui/profile']);

$this->params['northUserMenu'][] = [
    'id' => 'nav-profile',
    'text' => 'Profile',
    'iconCls' => 'icon-profile',
    'attributes' => [
        'url' => $profileUrl,
    ],
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
    'text' => 'Gray',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'gray']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Metro',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro']))
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
    'separator' => 1
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Material',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Material Teal',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material-teal']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Material Green',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material-green']))
];
$this->params['northUserMenu'][] = [
    'parent' => 'Theme',
    'text' => 'Material Blue',
    'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material-blue']))
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