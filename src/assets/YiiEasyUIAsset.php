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
class YiiEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/dist';
    // public $basePath = '@webroot/dist/jeasyui';
    // public $baseUrl = '@web/dist/jeasyui';
    public $css = [
        'css/yii.easyui.css',
        'css/icon.css'
    ];
    public $js = [
        'js/yii.easyui.min.js'
        //'js/yii.easyui.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\assets\JEasyUIAsset',
        'sheillendra\jeasyui\assets\FontAwesomeAsset'
    ];

    public function init() {
        $this->css[] = \sheillendra\jeasyui\components\helpers\AssetHelper::defaultTheme('css/themes/{theme}/yii.easyui.css');
        parent::init();
    }

}
