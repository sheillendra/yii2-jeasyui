<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/app/v2.0.0-alpha';
    public $css = ['css/login.css'];
    public $js = ['js/login.js'];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
