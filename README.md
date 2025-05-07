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

Change ```backend/config/main.php``` like this :

```
    'bootstrap' => ['log', 'devicedetect'],
    //remove jeasyui controller map if you have understand and want unwatch the default simulation
    //keep it to use login default
    'controllerMap' => [
        'jeasyui' => 'sheillendra\jeasyui\controllers\JeasyuiController'
    ],
    'components' =>[
        #... other 
        'user' => [
            #... other
            //change the line below if you understand the UserModel login action in jeasyui controller
            'identityClass' => 
            'urlLogin' => ['/jeasyui/login']
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => [
                        '@app/themes/jeasyui/views',
                        '@sheillendra/jeasyui/views',
                    ],
                    '@app/modules' => [
                        '@app/themes/jeasyui/modules',
                        '@sheillendra/jeasyui/modules',
                    ],
                    '@app/widgets' => [
                        '@app/themes/jeasyui/widgets',
                        '@sheillendra/jeasyui/widgets',
                    ]
                ],
            ],
        ],
        'devicedetect' => [
            'class' => 'sheillendra\jeasyui\components\devicedetect\DeviceDetect'
        ],
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