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

namespace <?= $generator->ns ?>;

use Yii;

/**
* This is the extended model class for table "<?= $generator->generateTableName($tableName) ?>",
* all customization about the model can be done here, 
* Why? because if there is a change in the table,
* you can re-generate the class using GII again to comply with the GII rules,
* and your changes in this file will not be lost if you uncheck it in the file list 
*/
class <?= $className . 'Ext' ?> extends <?= $className . "\n" ?>
{
<?php if(isset($tableSchema->columns['created_at'])):?>
    /**
     * @return type
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
        ];
    }
<?php endif?>

<?php foreach ($relationExts as $name => $relation) : ?>

    /**
     * @inheritdoc
     */
    public function get<?= $name ?>()
    {
    <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
}