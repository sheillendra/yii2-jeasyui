<?php

/**
 * @link http://www.sheillendra.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
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
