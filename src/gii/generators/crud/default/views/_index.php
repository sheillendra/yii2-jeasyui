<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

echo "<?php\n";
?>

use app\assets\<?=$modelClassName?>IndexAsset;
use yii\web\View;
use yii\helpers\Url;
?>

<?='<?php $this->beginPage();?>'?>
<?='<?php $this->head();?>'?>
<?='<?php $this->beginBody();?>'?>

<div class="easyui-layout" id="<?= $idModelClassName ?>-index" fit="true">
    <div region="north" style="height:30px" border="false">
        <div >
            <a id="<?= $idModelClassName ?>-index-new-btn">&nbsp;</a>
            <a id="<?= $idModelClassName ?>-index-del-btn">&nbsp;</a>
        </div>
    </div>
    <div region="west" style="width:50%" split="true">
        <div id="<?= $idModelClassName ?>-index-dg"></div>
    </div>
    <div region="center">
        <div id="<?= $idModelClassName ?>-index-pg"></div>
    </div>
</div>

<?='<?php $this->endBody();?>'."\n"?>
<?='<?php $this->endPage();?>'."\n"?>
<?="<?php\n"?>
$newUrl = Url::to(['<?=$idModelClassName?>/new'],true);
$deleteUrl = Url::to(['<?=$idModelClassName?>/delete'],true);
$getListDataUrl = Url::to(['<?=$idModelClassName?>/get-list-data'],true);
$this->registerJs(<<<EOD
    yii.<?=$varModelClassName?>Index.getListDataUrl = '{$getListDataUrl}';
    yii.<?=$varModelClassName?>Index.newUrl = '{$newUrl}';
    yii.<?=$varModelClassName?>Index.deleteUrl = '{$deleteUrl}';
    yii.<?=$varModelClassName?>Index.init();
EOD
    , View::POS_END );
<?=$modelClassName.'IndexAsset::register($this);'?>