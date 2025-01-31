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

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserAsset extends AssetBundle {

    //public $sourcePath = '@sheillendra/jeasyui/assets/dist';
    public $basePath = '@webroot/dist/jeasyui';
    public $baseUrl = '@web/dist/jeasyui';
    public $css = [
        'css/user.css',
    ];
    public $js = [
        'js/user.js'
    ];
    public $depends = [
        'sheillendra\jeasyui\assets\ExtDgFilterRowAsset',
        'sheillendra\jeasyui\assets\YiiEasyUIAsset'
    ];

    public function init() {
        parent::init();
    }

}
