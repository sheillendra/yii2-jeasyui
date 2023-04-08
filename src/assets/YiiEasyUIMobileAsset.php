<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
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
