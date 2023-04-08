<?php

/**
 * Implement jQuery EasyUI GPL Edition on Yii2
 * since    : v.0.0.2
 * author   : sheillendra
 * date     : 2017-05-28
 * website  : www.anjani.co.id
 */

namespace sheillendra\jeasyui\assets;

class JEasyUIMobileAsset extends JEasyUIAsset {
    public $css = [
        'themes/mobile.css',
        'themes/icon.css',
        'themes/color.css'
    ];
    
    public $js = [
        'jquery.easyui.min.js',
        'jquery.easyui.mobile.js'
    ];
}
