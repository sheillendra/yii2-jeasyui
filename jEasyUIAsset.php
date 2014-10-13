<?php
/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */

namespace sheillendra\jeasyui;

use Yii;
use yii\web\AssetBundle;

class jEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.4';
    public $css = [
        'themes/icon.css',
        'themes/color.css'
    ];
    public $js = ['easyloader.js'];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    //public $publishOptions=['forceCopy'=>YII_DEBUG];
}
