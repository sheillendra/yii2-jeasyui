<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/** @var yii\web\View $this */
/** @var sheillendra\jeasyui\gii\generators\crud\Generator $generator */

$extModelClass = StringHelper::basename($generator->extModelClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->extModelClass, '\\')) ?>;

/**
 * <?= $extModelClass ?> is extend of `<?= $generator->modelClass ?>`.
 */
class <?= $extModelClass ?> extends \<?= $generator->modelClass ?>

{
   
}
