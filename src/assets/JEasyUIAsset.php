<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : www.anjani.co.id
 */

namespace sheillendra\jeasyui\assets;

use Yii;
use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle {
    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.10.15';
    public $css = [
        'themes/default/easyui.css',
        'themes/icon.css',
        'themes/color.css',
    ];
    public $js = [
        //'easyloader.js',
        'jquery.easyui.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'sheillendra\jeasyui\assets\FontAwesomeAsset'
    ];
    public $publishOptions = [
        'except' => [
            'demo', 'demo-mobile', 'changelog.txt', 'jquery.min.js',
            'license_freeware.txt', 'readme.txt'
        ]
    ];

    public function init() {
        $themeCookies = filter_input(INPUT_COOKIE, 'jeasyui-theme');
        $themes = ['black', 'bootstrap', 'default', 'gray', 'material', 'metro',
            //'metro-blue', 'metro-gray', 'metro-green', 'metro-orange', 'metro-red',
            //'ui-cupertino', 'ui-dark-hive', 'ui-pepper-grinder','ui-sunny'
            'material-blue', 'material-teal'
        ];
        if ($themeCookies && in_array($themeCookies, $themes)) {
            $this->css[0] = "themes/$themeCookies/easyui.css";
        } else {
            $this->css[0] = "themes/default/easyui.css";
        }
        parent::init();
    }

}
