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
