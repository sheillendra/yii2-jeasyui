<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

$this->params['defaultSelectedNav'] = 'nav-dashboard';

return [
    [
        'id' => 'nav-dashboard',
        'text' => 'Home',
        'iconCls' => 'icon-house',
        'attributes' => [
            'url' => Url::to(['/'])
        ]
    ],
    [
        'text' => 'Demo',
        'iconCls' => 'icon-demo',
        'children' => [
            [
                'id' => 'nav-demo-application',
                'text' => 'Application',
                'iconCls' => 'icon-layout-content',
                'attributes' => [
                    'url' => Url::to(['/jeasyui/demo']),
                ]
            ]
        ]
    ],
    [
        'text' => 'Setting',
        'iconCls' => 'icon-cog',
        'children' => [
            [
                'id' => 'nav-setting',
                'text' => 'General',
                'iconCls' => 'icon-cog-edit',
                'attributes' => [
                    'url' => Url::to(['/jeasyui/setting']),
                ]
            ],
            [
                'id' => 'nav-setting-user',
                'text' => 'Users',
                'iconCls' => 'icon-group',
                'attributes' => [
                    'url' => Url::to(['/jeasyui/setting-user']),
                ]
            ],
            [
                'id' => 'nav-setting-rbac',
                'text' => 'Access Management',
                'iconCls' => 'icon-group-gear',
                'attributes' => [
                    'url' => Url::to(['/jeasyui/setting-rbac']),
                ]
            ]
        ]
    ]
];
