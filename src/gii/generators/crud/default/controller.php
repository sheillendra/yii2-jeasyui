<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$model = new ($generator->modelClass);
$labels = $model->attributeLabels();
if(method_exists($model, 'getEasyuiAttributes')){
    $easyuiAttributes = $model->easyuiAttributes;
} else {
    $easyuiAttributes = [];
}

$useExtendController = "\nuse sheillendra\jeasyui\components\web\Controller;";
$extendController = 'Controller';
if(isset($easyuiAttributes['_']) && isset($easyuiAttributes['_']['extendController'])){
    $useExtendController = '';
    $extendController = $easyuiAttributes['_']['extendController'];
}
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?=$appName?>\models\Permission;<?=$useExtendController?>

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClassName?> model.
 */
class <?=$controllerClass?> extends <?=$extendController?>
{
    public $rules = [
        [
            'actions' => ['index'],
            'allow' => true,
            'roles' => [Permission::READ_<?=strtoupper(Inflector::camel2id($baseModelClassName, '_'))?>_PERMISSION],
        ],
    ];
}
