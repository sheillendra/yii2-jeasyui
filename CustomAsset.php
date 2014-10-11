<?php

namespace sheillendra\jeasyui;

use Yii;
use yii\web\AssetBundle;

class CustomAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets';
    public $css = [
        'css/yii2-jeasyui.css'
    ];
    public $js = [
        'js/yii2-jeasyui.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\jEasyUIAsset',
        'sheillendra\theme\assets\FontAwesomeAsset'
    ];
    public $publishOptions=['forceCopy'=>YII_DEBUG];
    public static function register($view)
    {
        $view->registerJs('
            easyloader.theme="'.(isset(Yii::$app->params['jEasyUI']['theme'])?Yii::$app->params['jEasyUI']['theme']:'default').'";
            using(["'.implode('","',  array_unique($view->params['jEasyUI']['plugin'])).'"],function(){
            '.implode('',$view->params['jEasyUI']['command']).';
            });
        ',$view::POS_READY
        );
        return $view->registerAssetBundle(get_called_class());
    }
}
