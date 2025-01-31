<?php

use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->jeasyUiSearchModelClass);
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->apiName, '\\'). '\modules\jeasyui\controllers\\' . $controllerClass) ?>;

use yii\rest\Serializer;
use <?= $generator->jeasyUiSearchModelClass ?>;

/**
 * Implement search for JeasyUI format.
 */
class <?=$controllerClass?> extends \<?=$generator->apiControllerClass?>
{
    public $searchModelClass = <?=$modelClass?>::class;
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'rows'
    ];
}
