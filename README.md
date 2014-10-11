yii2-jeasyui
===============

Implement jQuery EasyUI GPL Edition on Yii2.

demo : http://demo.dodeso.com/index.php?r=jeasyui


Konsep implementasi ini bukanlah satu-satunya cara, masih banyak cara lain sesuai dengan kebutuhan kita.

Pada dasarnya penggunaan EasyUI sangatlah mudah, cukup buat element dan diberi kelas easyui-namaplugin maka
EasyUI akan otomatis merender element itu, namun cara itu akan terasa berat di browser jika element halaman kita banyak.

Dalam implementasi konsep saya ini, saya menggunakan Easyloader, dimana hanya plugin-plugin yang dibutuhkan saja
yang akan diload. Widget akan mengubah data inisialnya ke dalam javascript pada saat CustomAsset di register

layout\main.php
====
```
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use sheillendra\jeasyui\Easy;
use sheillendra\jeasyui\CustomAsset;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php AppAsset::register($this);?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<body>
    <?php $this->beginBody() ?>
    
    <?php
    Easy::Widget([
        'target'=>'body',
        'plugin'=>'layout',
        'clientOptions'=>['fit'=>true],
        'contents'=>[
            'north-main-layout'=>[
                'clientOptions'=>[
                    'region'=>'north',
                    'border'=>false,
                    'content'=>'Yii2 dan jQuery EasyUI'
                ]
            ],
            'west-main-layout'=>[
                'plugin'=>'layout',
                'clientOptions'=>[
                    'region'=>'west',
                    'width'=>200,
                    'split'=>true,
                ],
                'contents'=>[
                    'main-navigation'=>[
                        'plugin'=>'accordion',
                        'clientOptions'=>[
                            'fit'=>true
                            ,'border'=>false
                        ]
                        ,'contents'=>[
                            'nav-item-home' => [
                                'clientOptions'=>[
                                    'title'=>'Home'
                                    ,'iconCls'=>'fa fa-home'
                                    ,'selected'=>!isset($this->params['selected-nav']) || $this->params['selected-nav']==='home'
                                ]
                                ,'contents'=>[
                                    'nav-item-btn-home'=>[
                                        'plugin'=>'linkbutton',
                                        'clientOptions'=>[
                                            'title'=>'Home'
                                            ,'onClick'=>'function(){yii.app.createTab("main-tab-home","Home","'.Url::to(['/jeasyui'],true).'")}'
                                        ]
                                    ]
                                ]
                            ]
                            ,'datagrid-nav-item'=>[
                                'clientOptions'=>[
                                    'title'=>'Data Grid & Tree'
                                    ,'iconCls'=>'fa fa-dashboard'
                                    ,'selected'=>isset($this->params['selected-nav']) && $this->params['selected-nav']==='Data Grid'
                                ]
                                ,'contents'=>[
                                    'simple-grid-nav-btn'=>[
                                        'plugin'=>'linkbutton',
                                        'clientOptions'=>[
                                            'title'=>'Simple Grid'
                                            ,'onClick'=>'function(){yii.app.createTab("main-tab-data-grid-index","Simple Grid","'.Url::to(['/jeasyui/data-grid'],true).'",true)}'
                                        ]
                                    ]
                                    ,'property-grid-nav-btn'=>[
                                        'plugin'=>'linkbutton',
                                        'clientOptions'=>[
                                            'title'=>'Property Grid'
                                            ,'onClick'=>'function(){yii.app.createTab("main-tab-property-grid","Property Grid","'.Url::to(['/jeasyui/property-grid'],true).'",true)}'
                                        ]
                                    ]
                                ]
                            ]
                            ,'layout-nav-item'=>[
                                'clientOptions'=>[
                                    'title'=>'Layout'
                                    ,'iconCls'=>'fa fa-cogs'
                                    ,'selected'=>isset($this->params['selected-nav']) && $this->params['selected-nav']==='layout'
                                ]
                                ,'contents'=>[
                                    'complete-layout-nav-btn'=>[
                                        'plugin'=>'linkbutton',
                                        'clientOptions'=>[
                                            'title'=>'Complete Layout'
                                            ,'onClick'=>'function(){yii.app.createTab("main-tab-complete-layout-index","Complete Layout","'.Url::to(['/jeasyui/layout'],true).'",true)}'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'center-main-layout'=>[
                'clientOptions'=>[
                    'region'=>'center',
                    'border'=>false
                ],
                'contents'=>[
                    'main-tab'=>[
                        'plugin'=>'tabs',
                        'clientOptions'=>[
                            'fit'=>true
                            ,'onSelect'=>'function(t,i){yii.app.selectTab(t,i);}'
                        ],
                        'afterRegister'=>'yii.app.setMainTab($("#main-tab"));',
                        'contents'=>$this->params['content']
                    ]
                ]
            ],
            'south-main-layout'=>[
                'clientOptions'=>[
                    'region'=>'south',
                    'border'=>false,
                    'content'=>'footer here'
                ]
            ]
        ]
    ]);
    ?>
    <?php CustomAsset::register($this);?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

```

view : index.php
```
<?php
use yii\helpers\Url;

$this->params['selected-nav']='home';
$this->params['content']=[
    'main-tab-home'=>[
        'method'=>'add',
        'clientOptions'=>[
            'title'=>'Home',
            'data'=>['url'=>Url::to([''],true)],
            'closable'=>false,
            'iconCls'=>'fa fa-home',
            //bisa juga menggunakan $this->render('partials/_home',[],true) jika content cukup kompleks
            'content'=>'isi content di sini ' 
        ]
    ]
];
```
