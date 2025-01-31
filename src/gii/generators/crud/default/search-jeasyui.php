<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/** @var yii\web\View $this */
/** @var sheillendra\jeasyui\gii\generators\crud\Generator $generator */

$searchModelClass = StringHelper::basename($generator->jeasyUiSearchModelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->jeasyUiSearchModelClass, '\\')) ?>;

use sheillendra\jeasyui\components\helpers\DataFilterHelper;

/**
 * <?= $searchModelClass ?> only extends.
 */
class <?= $searchModelClass ?> extends \<?=$generator->apiName?>\models\<?= $searchModelClass ?>

{
    public function search($params)
    {
        return DataFilterHelper::prepareDataProvider(parent::search($params), $params);
    }
}
