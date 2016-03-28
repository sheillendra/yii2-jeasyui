<?php
use yii\helpers\Url;

$navItemUrl = [
    'dashboard' =>[
        'dashboard' => Url::to(['/site/index'], true)
    ]
];

$navItem = [
     '01' =>[
        'title'=>'Home',
        'iconCls'=>'icon-house',
        'content'=><<<HTML
            <a data-accordion="Home" id="nav-dashboard" class="nav-btn" data-icon="icon-chart-curve" data-url="{$navItemUrl['dashboard']['dashboard']}" data-tabtitle="Dashboard">Dashboard</a>
HTML
    ]
];
