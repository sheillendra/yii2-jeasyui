<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * email    : suryana869@gmail.com
 * contact  : +6281242126699 (whatsapp, viber)
 * date     : 2014-10-04
 * website  : https://esppd.fly.dev/
 */

namespace sheillendra\jeasyui\assets;

use Yii;
use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle
{
    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.11.1';
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

    public function init()
    {
        // $themeCookies = filter_input(INPUT_COOKIE, 'jeasyui-theme');
        // $themes = [
        //     'black',
        //     'bootstrap',
        //     'default',
        //     'gray',
        //     'material',
        //     'material-blue',
        //     'material-teal',
        //     'metro',
        // ];
        // if ($themeCookies && in_array($themeCookies, $themes)) {
        //     $this->css[0] = "themes/$themeCookies/easyui.css";
        // } else {
        //     $this->css[0] = "themes/default/easyui.css";
        // }

        $this->css[0] = \sheillendra\jeasyui\components\helpers\AssetHelper::defaultTheme();
        parent::init();
    }
}
