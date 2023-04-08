Warning !!! in master-branch there is some break code.

yii2-jeasyui
===============

Implement jQuery EasyUI GPL Edition on Yii2.

INSTALLATION
---
The preferred way to install this extension is through composer.

Either run
```
php composer.phar require sheillendra/yii2-jeasyui "dev-master"
```
or add
```
"sheillendra/yii2-jeasyui": "dev-master"
```
to the require section of your composer.json file.

USAGE
---

in bootstrap.php add line :
```
Yii::setAlias('@sheillendra/jeasyui', dirname(__DIR__) . '/extensions/sheillendra/yii2-jeasyui/src');
```
this test use advanced template, so bootstrap.php place in common/config


in config.php change view components like this :

```
    'bootstrap' => ['log', 'devicedetect'],
    //remove jeasyui controller map if you have understand and want unwatch the default simulation
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
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
    ]
```

In here you can see the default simulation. Recommended for ```backend``` in ```advanced```,
but if you want implement it in ```basic``` make sure your controller have implement ```AccessControl``` behavior
for logged user only can access the controller.


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

## yii.easyui.min.js
```
Method :
.showMainMask()
.hideMainMask()
.cookie.set(name, value, days, path)
.cookie.get(name)
.cookie.delete(name, path)
```