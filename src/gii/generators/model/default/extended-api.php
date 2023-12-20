<?php

/**
 * This is the template for generating the extend model class of a specified table.
 */

/** @var yii\web\View $this */
/** @var yii\gii\generators\model\Generator $generator */
/** @var string $tableName full table name */
/** @var string $className class name */
/** @var string $queryClassName query class name */
/** @var yii\db\TableSchema $tableSchema */
/** @var array $properties list of properties (property => [type, name. comment]) */
/** @var string[] $labels list of attribute labels (name => label) */
/** @var string[] $rules list of validation rules */
/** @var array $relations list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ans . '\models' ?>;

use Yii;

/**
* @inheritdoc
*/
class <?= $className . 'Ext' ?> extends <?= '\\' . $generator->ns . '\\' . $className . 'Ext' . "\n" ?>
{

}