<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : sheillendra.com
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class ExtDgFilterRowAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/extensions/datagrid-filter';
    public $css = [];
    public $js = [
        'datagrid-filter.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\JEasyUIAsset'
    ];
    public $publishOptions = [
        'only' => [
            'datagrid-filter.js'
        ]
    ];
}
