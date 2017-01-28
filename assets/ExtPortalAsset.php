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

class ExtPortalAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/extensions';
    public $css = [];
    public $js = [
        'jquery.portal.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\jEasyUIAsset'
    ];
}
