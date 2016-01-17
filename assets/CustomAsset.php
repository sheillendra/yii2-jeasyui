<?php
/**
 * Implement jQuery EasyUI v.1.4 GPL Edition on Yii2
 * version  : v.0.0.1
 * author   : sheillendra
 * date     : 2014-10-04
 * website  : demo.dodeso.com
 */

namespace sheillendra\jeasyui\assets;

use Yii;
use yii\web\AssetBundle;
use yii\helpers\Json;

class CustomAsset extends AssetBundle {

    public $sourcePath = '@sheillendra/jeasyui/assets/custom';
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
            yii.jeasyui.appendToParents('.Json::encode(Easy::$appendToParents).');
            yii.jeasyui.addContentMethod('.Json::encode(Easy::$addContentMethod).');
            easyloader.theme="'.(isset(Yii::$app->params['jEasyUI']['theme'])?Yii::$app->params['jEasyUI']['theme']:'default').'";
            using(["'.implode('","',  array_unique($view->params['jEasyUI']['plugin'])).'"],function(){
            '.implode('',$view->params['jEasyUI']['command']).';
            });
        ',$view::POS_READY
        );
        return $view->registerAssetBundle(get_called_class());
    }
}
