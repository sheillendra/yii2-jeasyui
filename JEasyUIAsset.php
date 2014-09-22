<?php

namespace sheillendra\jeasyui;

use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.4';
    public $css = [];
    public $js = ['easyloader.js'];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
