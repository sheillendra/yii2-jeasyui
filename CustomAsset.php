<?php

namespace sheillendra\jeasyui;

use Yii;
use yii\web\AssetBundle;

class CustomAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets';
    public $css = [
        'css/yii2-jeasyui.css'
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $publishOptions=['forceCopy'=>YII_DEBUG];
}
