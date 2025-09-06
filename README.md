yii2-jeasyui
===============

Implement jQuery EasyUI GPL Edition on Yii2.

[Static Demo](https://sheillendra.github.io/yii2-jeasyui/)

INSTALLATION
---
The preferred way to install this extension is through composer.

Either run
```
php composer.phar require sheillendra/yii2-jeasyui "2.0.1.1"
```
or add
```
"sheillendra/yii2-jeasyui": "2.0.1.1"
```
to the require section of your composer.json file.

USAGE
---
In this case used advanced template and backend

1. common\config\bootstrap.php
```
Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/uploads');
Yii::setAlias('@sheillendra/jeasyui', dirname(__DIR__) . '/extensions/sheillendra/yii2-jeasyui/src');
```

2. Change ```backend/config/main.php``` like this :
```
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class' => 'backend\modules\api\modules\v1\Module',
                    'modules' => [
                        'jeasyui' => [
                            'class' => 'backend\modules\api\modules\v1\modules\jeasyui\Module',
                        ]    
                    ]
                ]    
            ]
        ],
        'jeasyui' => [
            'basePath' => '@sheillendra/jeasyui',
            'class' => 'sheillendra\jeasyui\Module',
            'modules' => [
                'api' => [
                    'basePath' => '@sheillendra/jeasyui/modules/api',
                    'class' => 'sheillendra\jeasyui\modules\api\Module',
                    'modules' => [
                        'jeasyui' => [
                            'basePath' => '@sheillendra/jeasyui/modules/api/modules/jeasyui',
                            'class' => 'sheillendra\jeasyui\modules\api\modules\jeasyui\Module',
                        ]
                    ]
                ]
            ]
        ]
    ],

    'components' => [
        ...
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => [
                        '@app/themes/jeasyui/views',
                        '@common/themes/jeasyui/views',
                        '@sheillendra/jeasyui/views',
                        '@app/themes/basic/views'
                    ],
                    '@app/modules' => [
                        '@app/themes/jeasyui/modules',
                        '@sheillendra/jeasyui/views',
                        '@app/themes/basic/modules',
                    ],
                    '@app/widgets' => [
                        '@app/themes/jeasyui/widgets',
                        '@sheillendra/jeasyui/views',
                        '@app/themes/basic/widgets',
                    ]
                ],
            ],
        ],
        ...
    ]
```

## GII

```
// config/main-local.php        for yii2-app-advanced
// config/web.php               for yii2-basic
...
if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment 
    ...
    
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
       'generators' =>[
           'jeasyui-crud' => ['class'=>'sheillendra\jeasyui\gii\generators\crud\Generator']
        ]
    ];
}
```

### Put GII instruction in modelExt (extended)
```
    /**
     * For generate EasyUI
     */
    public function getEasyuiAttributes()
    {
        return [
            '_' => [
                'formDialogHeight' => 500
            ],
            'id' => [
                'width' => 60,
            ],
            'fix_price' => [
                'formHide' => true,
                'format' => 'currency'
            ],
            'pdf_file' => [
                'input' => 'file',
                'accept' => 'pdf',
                'inputName' => 'inputFile',
            ],
            'date' => [
                'validType' => "'validDate','dateLessEqual[]'"
            ]
        ];
    }
```


## yii.easyui.min.js
```
Method :
.showMainMask()
.hideMainMask()
.cookie.set(name, value, days, path)
.cookie.get(name)
.cookie.delete(name, path)
```