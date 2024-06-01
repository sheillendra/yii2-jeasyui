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

/**
 * @author Sheillendra <sheillendra@yahoo.com>
 * @since 2.0
 */
class D3Asset extends AssetBundle {

    public $sourcePath = '@bower/d3';
    public $css = [];
    public $js = [
        'd3.min.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\JEasyUIAsset',
    ];
    public $publishOptions = [
        'only' => ['d3.min.js']
    ];
}
