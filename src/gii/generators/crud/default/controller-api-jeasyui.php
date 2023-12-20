<?php

use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->apiName, '\\'). '\modules\jeasyui\controllers\\' . $controllerClass) ?>;

/**
 * Implement search for JeasyUI format.
 */
class <?=$controllerClass?> extends \<?=$generator->apiControllerClass?>
{
    public $modelClass = <?=$modelClass?>::class;
}
