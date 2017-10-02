<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : www.bjuta.com
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.5.3';
    public $css = [
        'themes/icon.css',
        'themes/color.css',
        'themes/default/easyui.css',
    ];
    public $js = [
        //'easyloader.js',
        'jquery.easyui.custom.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'sheillendra\jeasyui\assets\FontAwesomeAsset'
    ];
    public $publishOptions = [
        'except' => [
            'demo', 'demo-mobile', 'changelog.txt', 'jquery.min.js',
            'license_freeware.txt', 'readme.txt'
        ]
    ];
}
