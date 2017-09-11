<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/dist/v2.0.0-alpha';
    public $css = ['css/login.css'];
    public $js = ['js/login.min.js'];
    
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
//    public $css = [
//        'jeasyui/v2.0.0-alpha/css/login.css'
//    ];
//    public $js = [
//        'jeasyui/v2.0.0-alpha/js/login.js',
////        'jeasyui/v2.0.0-alpha/js/login.min.js'
//    ];
    
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
