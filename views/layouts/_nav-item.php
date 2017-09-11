<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

/**
 * key like '01' is only for sorting
 */
return [
    [
        'title' => 'Home',
        'iconCls' => 'icon-house',
        'content' => $this->render('@app/views/layouts/_nav-item_content', [
            'accordionItemName' => 'Home',
            'id' => 'nav-dashboard',
            'icon' => 'icon-chart-curve',
            'url' => Url::to(['/jeasyui']),
            'label' => 'Dashboard'
                ], true
        )
    ],
    [
        'title' => 'Setting',
        'iconCls' => 'icon-house',
        'content' => $this->render('@app/views/layouts/_nav-item_content', [
            'accordionItemName' => 'Setting',
            'id' => 'nav-setting',
            'icon' => 'icon-chart-curve',
            'url' => Url::to(['/jeasyui/setting']),
            'label' => 'Dashboard'
                ], true
        )
    ]
];
