<?php

echo "<?php\n";

?>
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\views\site\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle {

    public $sourcePath = '@app/views/site/assets';
    public $css = ['css/login.css'];
    public $js = ['js/login.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'sheillendra\jeasyui\jEasyUIAsset',
    ];
    public $publishOptions=['forceCopy'=>YII_DEBUG];
}
