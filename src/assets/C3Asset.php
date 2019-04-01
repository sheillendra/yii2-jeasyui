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
class C3Asset extends AssetBundle {

    public $sourcePath = '@bower/c3';
    public $css = [
        'c3.min.css',
    ];
    public $js = [
        'c3.min.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\D3Asset',
    ];
    public $publishOptions = [
        'only' => [
            'c3.min.css', 'c3.min.js'
        ]
    ];
}
