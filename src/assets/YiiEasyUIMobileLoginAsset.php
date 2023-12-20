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

class YiiEasyUIMobileLoginAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/dist';
    public $css = ['css/yii.easyui-mobile-login.css'];
    public $js = ['js/yii.easyui-mobile-login.min.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\assets\JEasyUIMobileAsset'
    ];

}
