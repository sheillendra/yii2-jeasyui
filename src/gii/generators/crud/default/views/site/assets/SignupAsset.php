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

class SignupAsset extends AssetBundle {

    public $sourcePath = '@app/views/site/assets';
    public $css = ['css/signup.css'];
    public $js = ['js/signup.js'];
    public $depends = [];
    public $publishOptions=['forceCopy'=>YII_DEBUG];
}
