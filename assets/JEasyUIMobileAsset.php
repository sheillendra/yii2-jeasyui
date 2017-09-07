<?php

/**
 * Implement jQuery EasyUI GPL Edition on Yii2
 * since    : v.0.0.2
 * author   : sheillendra
 * date     : 2017-05-28
 * website  : www.bjuta.com
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.5.3';
    public $css = [
        'themes/mobile.css',
        'themes/icon.css',
        'themes/color.css'
    ];
    public $js = [
        'easyloader.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'sheillendra\jeasyui\assets\FontAwesomeAsset'
    ];
}
