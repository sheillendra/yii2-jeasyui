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

class ExtPortalAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/extensions/jquery-easyui-portal';
    public $css = [];
    public $js = [
        'jquery.portal.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\JEasyUIAsset'
    ];
    public $publishOptions = [
        'only' => [
            'jquery.portal.js'
        ]
    ];
}
