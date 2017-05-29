<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

/**
 * key like '01' is only for sorting
 */
return [
    '01' => [
        'title' => 'Home',
        'iconCls' => 'icon-house',
        'content' => $this->render('@app/views/layouts/_nav-item_content', [
            'accordionItemName' => 'Home',
            'id' => 'nav-dashboard',
            'icon' => 'icon-chart-curve',
            'url' => Url::to(['/site/index']),
            'label' => 'Dashboard'
                ], true
        )
    ]
];
