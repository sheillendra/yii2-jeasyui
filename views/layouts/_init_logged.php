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
$this->params['northUserMenu'] = [
    [
        'text' => 'Profile',
        'iconCls' => 'icon-profile',
        'onclick' => new JsExpression($profileOnClick)
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
