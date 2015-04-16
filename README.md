yii2-jeasyui
===============

Implement jQuery EasyUI GPL Edition on Yii2.

GII 
---

Set jeasyui-gii in your config :
```
$config['modules']['gii'] = [
    'class'=>'yii\gii\Module',
    'generators' =>[
        'jeasyui-crud' => ['class'=>'sheillendra\jeasyui\gii\generators\crud\Generator']
    ]
];
```