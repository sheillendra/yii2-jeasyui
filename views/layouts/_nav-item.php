<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

if ($this->params['sidebarPlugin'] === 'tree') {
    return [
        [
            'id' => 'nav-dashboard',
            'text' => 'Home',
            'iconCls' => 'icon-house',
            'attributes' => [
                'url' => Url::to(['/jeasyui'])
            ]
        ],
        [
            'text' => 'Setting',
            'iconCls' => 'icon-cog',
            'children' => [
                [
                    'id' => 'nav-setting',
                    'text' => 'General',
                    'iconCls' => 'icon-cog',
                    'attributes' => [
                        'url' => Url::to(['/jeasyui/setting']),
                    ]
                ],
                [
                    'id' => 'nav-setting-user',
                    'text' => 'Users',
                    'iconCls' => 'icon-cog',
                    'attributes' => [
                        'url' => Url::to(['/jeasyui/setting-user']),
                    ]
                ],
                [
                    'id' => 'nav-setting-rbac',
                    'text' => 'Access Management',
                    'iconCls' => 'icon-cog',
                    'attributes' => [
                        'url' => Url::to(['/jeasyui/setting-rbac']),
                    ]
                ]
            ]
        ]
    ];
} else {
    // accordion mode :
    return [
        [
            'title' => 'Home',
            'iconCls' => 'icon-house',
            'content' => $this->render('@app/views/layouts/_nav-item_accordion_content', [
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
            'iconCls' => 'icon-cog',
            'content' => $this->render('@app/views/layouts/_nav-item_accordion_content', [
                'accordionItemName' => 'Setting',
                'id' => 'nav-setting',
                'icon' => 'icon-cog',
                'url' => Url::to(['/jeasyui/setting']),
                'label' => 'General'
                    ], true
            )
        ]
    ];
}
