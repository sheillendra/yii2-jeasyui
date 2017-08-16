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

in config.php change view components like this :

```
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

in your base application create themes folder with structure like this :

```
assets\
config\
controllers\
models\
modules\
runtime\
themes\
    jeasyui\
        views\
            layouts\                            //left blank if you want see the default
                _nav_item.php                   //this your customize nav layout will ovverride to sheillendra\layouts
                _north_content.php              //this your customize north layout will ovverride to sheillendra\layouts
                _south_content.php              //this your customize south layout will ovverride to sheillendra\layouts
                _west_content.php               //this your customize west layout will ovverride to sheillendra\layouts
            modules\
                module1\
                    views\
                        view_of_controller11
                        view_of_controller12
                module2\
                    views\
                        view_of_controller21
                        view_of_controller22
            widgets\
            site\                               //this is view of SiteController
            ...                                 //other_view_of_controller
        
views\
web\
```
