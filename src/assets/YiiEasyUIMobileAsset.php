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
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class YiiEasyUIMobileAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/dist';
    //public $sourcePath = '@sheillendra/jeasyui/assets/dist/v2.0.0-alpha.3';
    public $css = [
        'css/yii.easyui-mobile.css',
        'css/icon.css'
    ];
    public $js = [
        'js/yii.easyui-mobile.min.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\assets\JEasyUIMobileAsset'
    ];

}
