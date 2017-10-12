<?php

/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * since    : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : www.bjuta.com
 */

namespace sheillendra\jeasyui\assets;

use Yii;
use yii\web\AssetBundle;

class JEasyUIAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/jquery-easyui-1.5.3';
    public $css = [
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
//        $cookies = Yii::$app->request->cookies;
//        $themeCookies = $cookies->get('jeasyui-theme');
//        $addCookie = false;
//        if (!$themeCookies) {
            $themeCookies = filter_input(INPUT_COOKIE, 'jeasyui-theme', FILTER_SANITIZE_STRING);
//            $addCookie = true;
//        }

        $themes = ['black', 'bootstrap', 'default', 'gray', 'material', 'metro'];
        if ($themeCookies && in_array($themeCookies, $themes)) {
            $this->css[] = "themes/$themeCookies/easyui.css";
//            if ($addCookie) {
//                Yii::$app->response->cookies->add(new \yii\web\Cookie([
//                    'name' => 'jeasyui-theme',
//                    'value' => $themeCookies
//                ]));
//            }
        } else {
            $this->css[] = 'themes/default/easyui.css';
        }
        parent::init();
    }

}
