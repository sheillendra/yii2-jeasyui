<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

$glue = <<<EOD
                    </td>
                </tr>
                <tr>
                    <td>
                    
EOD;

$labels = $generator->generateLabels();

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();

if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use app\assets\<?=$modelClassName?>NewAsset;

/* @var $this yii\web\View */
/* @var $model app\models\<?=$modelClassName?> */
?>

<?= '<?php $this->beginPage(); ?>'."\n" ?>
<?= '<?php $this->head(); ?>'."\n" ?>
<?= '<?php $this->beginBody(); ?>'."\n" ?>
<div class="easyui-layout" fit="true">
    <div region="north" border="false">
        <div class="toolbar">
            <a id="<?=$idModelClassName?>-save-btn" class="easyui-linkbutton" icon="icon-save" plain="true">Save</a>
            <a id="<?=$idModelClassName?>-clear-btn" class="easyui-linkbutton" icon="icon-cancel" plain="true">Clear</a>
        </div>
    </div>
    <div region="center" border="false" style="padding:5px;">
        <?="<?=Html::beginForm(['{$idModelClassName}/new'],'',['id'=>'{$idModelClassName}-new-form'])?>\n"?>
        <table width="100%">
            <tbody>
                <tr>
                    <td>
                    <?php
                    $field = [];
                    foreach ($generator->getColumnNames() as $attribute) {
                        if (in_array($attribute, $safeAttributes)) {
                        	$field[] = "    " . $labels[$attribute] . "\n";
                            $field[] = "    <?= " . $generator->generateField($attribute) . " ?>\n";
                        }
                    }
                    echo implode($glue,$field);
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?="<?=Html::endForm()?>\n"?>
    </div>
</div>
<?= '<?php $this->endBody(); ?>'."\n" ?>
<?= '<?php $this->endPage(); ?>'."\n" ?>
<?= "<?php\n" ?>
$this->registerJs(<<<EOD
    yii.<?= $varModelClassName ?>New.init();
EOD
    , View::POS_END);
<?= $modelClassName . 'NewAsset::register($this);' ?>
