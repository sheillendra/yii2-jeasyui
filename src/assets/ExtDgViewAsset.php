<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * email    : suryana869@gmail.com
 * contact  : +6281242126699 (whatsapp, viber)
 * date     : 2014-10-04
 * website  : https://esppd.fly.dev/
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class ExtDgViewAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/extensions/jquery-easyui-datagridview';
    public $css = [];
    public $js = [
        'datagrid-bufferview.js',
        'datagrid-defaultview.js',
        'datagrid-detailview.js',
        'datagrid-groupview.js',
        'datagrid-scrollview.js',
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\JEasyUIAsset'
    ];
    public $publishOptions = [
        'only' => [
            'images/*',
            'datagrid-bufferview.js',
            'datagrid-defaultview.js',
            'datagrid-detailview.js',
            'datagrid-groupview.js',
            'datagrid-scrollview.js',
        ]
    ];

}
