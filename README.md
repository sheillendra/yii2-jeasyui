yii2-jeasyui
===============

Implement jQuery EasyUI GPL Edition on Yii2.

INSTALLATION
---
The preferred way to install this extension is through composer.

Either run

php composer.phar require sheillendra/yii2-jeasyui "dev-master"

or add

"sheillendra/yii2-jeasyui": "dev-master"

to the require section of your composer.json file.

USAGE 
---

**Gii**

This extension come with gii for JEasyUi and You can see usage via this Gii.
Once the extension is installed, simply modify your application configuration as follows:
```
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
