<?php

/**
 * @link http://www.sheillendra.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sheillendra\jeasyui\assets;

use yii\web\AssetBundle;

/**
 * @author Sheillendra <sheillendra@yahoo.com>
 * @since 2.0
 */
class FontAwesomeAsset extends AssetBundle {

    public $sourcePath = '@bower/font-awesome';
    public $css = [
        'web-fonts-with-css/css/fontawesome.min.css',
    ];
    public $js = [];
    public $depends = [];
    public $publishOptions = [
        'only' => [
            'web-fonts-with-css/css/fontawesome.min.css', 'web-fonts-with-css/webfonts/*'
        ]
    ];
}
