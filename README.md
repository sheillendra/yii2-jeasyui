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

INSTALLATION
---

in config.php change view components like this :

```
    //remove jeasyui controller map if you have understand and want unwatch the default simulation
    'controllerMap' => [
        'jeasyui' => 'sheillendra\jeasyui\controllers\JeasyuiController'
    ],
    'components' =>[
        #... other 
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
    ]
```

In here you can see the default simulation. Recommended for ```backend``` in ```advanced```,
but if you want implement it in ```basic``` make sure your controller have implement ```AccessControl``` behavior
for logged user only can access the controller.

## CUSTOMIZE

Before customize/replace parts of this extension, you should create themes folder with structure like this :

```
assets\
config\
controllers\
models\
modules\
runtime\
themes\
    jeasyui\
        modules\                                //modules view
            module1\
                views\
                    view_of_controller11
                    view_of_controller12
            module2\
                views\
                    view_of_controller21
                    view_of_controller22
        views\
            layouts\                            //left blank if you want see the default
                _init                           //initialization variable
                _nav_item.php                   //this your customize nav layout will ovverride to sheillendra\layouts
                _north_content.php              //this your customize north layout will ovverride to sheillendra\layouts
                _south_content.php              //this your customize south layout will ovverride to sheillendra\layouts
                _west_content.php               //this your customize west layout will ovverride to sheillendra\layouts
            site\                               //this is view of SiteController
            ...                                 //other_view_of_controller

        widgets\

views\                                          //default views, view non-themes
web\                                            //web, public accessed folder
```
Choose one from structure above what you want replace it, not must all.

In ```advanced``` alias ```@app``` means ```backend``` or ```frontend``` according to what application implement it.

### _init

create ```@app\themes\jeasyui\layouts\_init.php``` copy the content from ```sheillendra\jeasyui\layouts\_init.php``` and adjust to your needs.
This file is replace not override, see inheritance theme documentation.
