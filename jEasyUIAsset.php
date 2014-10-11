<?php

namespace sheillendra\jeasyui;

use Yii;
use yii\web\AssetBundle;

class jEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.4';
    public $css = [
        
    ];
    public $js = ['easyloader.js'];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
