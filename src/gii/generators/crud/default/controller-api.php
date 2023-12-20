<?php

use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->apiName);
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->apiControllerClass, '\\')) ?>;

use sheillendra\jeasyui\components\rest\ActiveController;
use <?=$generator->apiName?>\models\<?=$modelClass?>;

/**
 * <?=$controllerClass?> only implements the jEasyUI get asset and page for <?=$modelClass?> model.
 */
class <?=$controllerClass?> extends ActiveController
{
    public $modelClass = <?=$modelClass?>::class;
}
