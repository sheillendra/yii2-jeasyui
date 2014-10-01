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
        'yii\web\YiiAsset',
    ];
    
    public static function register($view)
    {
        $view->registerJs('
            easyloader.theme="'.(isset(Yii::$app->params['jEasyUI']['theme'])?Yii::$app->params['jEasyUI']['theme']:'default').'";
            using(["'.implode('","',  array_unique($view->params['jEasyUI']['plugin'])).'"],function(){
            '.implode(';',$view->params['jEasyUI']['command']).';
            });
        ',$view::POS_READY
        );
        return $view->registerAssetBundle(get_called_class());
    }
}
