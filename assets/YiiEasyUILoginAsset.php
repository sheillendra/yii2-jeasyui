<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class YiiEasyUILoginAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/dist/v2.0.0-alpha.3';
    public $css = ['css/yii.easyui-login.css'];
    public $js = ['js/yii.easyui-login.min.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\assets\JEasyUIAsset'
    ];

}
