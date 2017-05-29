<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\views\site\assets;

use yii\web\AssetBundle;

class SignupAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/app';
    public $css = ['css/signup.css'];
    public $js = ['js/signup.js'];
    public $depends = [
         'yii\web\YiiAsset'
    ];
}
