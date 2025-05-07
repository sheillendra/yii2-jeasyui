<?php

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

/**
 * Init Data asset bundle.
 */
class InitDataAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jeasyui/init-data.css',
    ];
    public $js = [
        'js/jeasyui/init-data.js',
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\ExtDgFilterRowAsset',
        'backend\themes\jeasyui\assets\AppAsset'
    ];
}