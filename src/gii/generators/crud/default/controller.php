<?php

use yii\helpers\StringHelper;

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use sheillendra\jeasyui\components\web\Controller;

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClassName?> model.
 */
class <?=$controllerClass?> extends Controller
{
 
}
