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
class YiiEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/dist/v2.0.0-alpha';
    public $css = [
        'css/app.css',
        'css/icon.css'
    ];
    public $js = [
        'js/yii.easyui.min.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\assets\JEasyUIAsset'
    ];

}
