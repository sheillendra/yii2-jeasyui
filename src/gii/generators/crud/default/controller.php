<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?=$appName?>\models\Permission;
use sheillendra\jeasyui\components\web\Controller;

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClassName?> model.
 */
class <?=$controllerClass?> extends Controller
{
    public $rules = [
        [
            'actions' => ['index'],
            'allow' => true,
            'roles' => [Permission::READ_<?=strtoupper(Inflector::camel2id($baseModelClassName, '_'))?>_PERMISSION],
        ],
    ];
}
