<?php
use yii\helpers\Inflector;

if(substr($modelClassName, -3, 3) === 'Ext'){
    $modelClassName = substr($modelClassName, 0, -3);
}
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

echo "<?php\n";
?>
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace <?= $generator->appName ?>\themes\jeasyui\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class <?=$baseAssetName?> extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jeasyui/<?=$idModelClassName?>.css',
    ];
    public $js = [
        'js/jeasyui/<?=$idModelClassName?>.js'
    ];
    public $depends = [
        '<?= $generator->appName ?>\themes\jeasyui\assets\AppAsset',
        'sheillendra\jeasyui\assets\ExtDgFilterRowAsset',
    ];
}
