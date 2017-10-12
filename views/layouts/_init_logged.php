<?php

use yii\helpers\Url;
use yii\web\JsExpression;

$this->params['favico'] = Yii::getAlias('@web') . '/favicon.png';
$this->title = 'Admin - ' . $this->title;

$this->params['userName'] = Yii::$app->user->identity->username;

$profileUrl = Url::to(['/jeasyui/profile'], true);
$logoutUrl = Url::to(['/jeasyui/logout'], true);
$profileOnClick = <<<JS
    function (e) {
        yii.easyui.createTab('Profile', '{$profileUrl}', 'icon-profile', 'nav-profile');
        e.stopPropagation();
    }
JS;
$logoutOnClick = <<<JS
    function (e) {
        yii.handleAction($('<a href="{$logoutUrl}" data-method="post"></a>'));
        e.stopPropagation();
    }
JS;
        
$templateThemeOnClick = <<<JS
    function (e) {
        yii.easyui.cookie.delete('jeasyui-theme');
        yii.easyui.cookie.set('jeasyui-theme', '{thema}');
        window.location.reload(false);
        e.stopPropagation();
    }
JS;
        
$this->params['northUserMenu'] = [
    [
        'text' => 'Profile',
        'iconCls' => 'icon-profile',
        'onclick' => new JsExpression($profileOnClick)
    ],
    ['separator' => 1],
    [
        'text' => 'Theme',
        'iconCls' => 'icon-profile',
    ],
    [
        'parent' => 'Theme',
        'text' => 'Default',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'default']))
    ],
    [
        'parent' => 'Theme',
        'text' => 'Black',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'black']))
    ],
    [
        'parent' => 'Theme',
        'text' => 'Bootstrap',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'bootstrap']))
    ],
    [
        'parent' => 'Theme',
        'text' => 'Gray',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'gray']))
    ],
    [
        'parent' => 'Theme',
        'text' => 'Material',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'material']))
    ],
    [
        'parent' => 'Theme',
        'text' => 'Metro',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => 'metro']))
    ],
    ['separator' => 1],
    [
        'text' => 'Logout',
        'iconCls' => 'icon-door-out',
        'onclick' => new JsExpression($logoutOnClick)
    ]
];
$this->params['getReferenceUrl'] = Url::to(['/jeasyui/reference'], true);

/**
 * sidebar plugin : tree or accordion
 */
$this->params['sidebarPlugin'] = 'tree';
