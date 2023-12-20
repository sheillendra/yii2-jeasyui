<?php

use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use sheillendra\jeasyui\components\web\Controller;

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClass?> model.
 */
class <?=$controllerClass?> extends Controller
{
 
}
