<?php

use yii\helpers\Url;
use yii\web\JsExpression;

$this->params['northUserMenu'] = [];

//custom from here
$profileUrl = Url::to(['/jeasyui/profile']);

$this->params['northUserMenu'][] = [
    'id' => 'nav-profile',
    'text' => 'Profile',
    'iconCls' => 'fa-solid fa-address-card',
    'attributes' => [
        'url' => $profileUrl,
    ],
    'onclick' => new JsExpression(
        <<<JS
        function (e) {
            yii.easyui.createTab({
                title: 'Profile',
                href: '{$profileUrl}',
                iconCls: 'fa-solid fa-address-card'
            }, 'nav-profile');
            e.stopPropagation();
        }
JS
    )
];

$this->params['northUserMenu'][] = ['separator' => 1];
$this->params['northUserMenu'][] = [
    'text' => 'Theme',
    'iconCls' => 'fa-solid fa-palette',
];

$templateThemeOnClick = <<<JS
    function (e) {
        yii.easyui.cookie.delete('jeasyui-theme');
        yii.easyui.cookie.set('jeasyui-theme', '{thema}');
        window.location.reload(false);
        e.stopPropagation();
    }
JS;

$defaultTheme = \sheillendra\jeasyui\components\helpers\AssetHelper::defaultTheme('{theme}');
foreach (\sheillendra\jeasyui\components\helpers\AssetHelper::THEMES as $k=>$v){
    $this->params['northUserMenu'][] = [
        'parent' => 'Theme',
        'text' => $v,
        'iconCls' => $defaultTheme == $v? 'fa-solid fa-check':'',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => $v]))
    ];
}

$this->params['northUserMenu'][] = ['separator' => 1];

$this->params['northUserMenu'][] = [
    'text' => 'Logout',
    'iconCls' => 'fa-solid fa-right-from-bracket',
    'onclick' => new JsExpression(
        <<<JS
        function (e) {
            yii.handleAction($('<a href="{$this->params['logoutUrl']}" data-method="post"></a>'));
            e.stopPropagation();
        }
JS
    )
];
