<?php
use yii\helpers\Url;

$navItemUrl = [
    'dashboard' =>[
        'dashboard' => Url::to('/', true)
    ]
];

$navItem = [
    'dashboard'=>[
        'title'=>'Dashboard',
        'iconCls'=>'icon-tip',
        'content'=><<<HTML
            <a id="nav-dashboard" class="nav-btn" data-icon="icon-help" data-url="{$navItemUrl['dashboard']['dashboard']}" data-tabtitle="Dashboard">Dashboard</a>
HTML
    ]
];