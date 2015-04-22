<?php
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

echo "<?php\n";
?>
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class <?=$modelClassName?>NewAsset extends AssetBundle
{
    public $sourcePath = '@app/views/<?=$idModelClassName?>/assets';
    public $css = [
        'css/<?=$idModelClassName?>-new.css',
    ];
    public $js = [
        'js/<?=$idModelClassName?>-new.js'
    ];
    public $depends = [];
    public $publishOptions=['forceCopy'=>YII_DEBUG];
}
