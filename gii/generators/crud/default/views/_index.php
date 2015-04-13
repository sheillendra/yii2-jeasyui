<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>

use app\views\<?= Inflector::camel2id(StringHelper::basename($generator->modelClass))?>\assets\<?=StringHelper::basename($generator->modelClass)?>IndexAsset;
use yii\web\View;

?>

<?='<?php $this->beginPage();?>'?>
<?='<?php $this->head();?>'?>
<?='<?php $this->beginBody();?>'?>

<div class="easyui-layout" id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index" fit="true">
    <div region="north" style="height:30px" border="false">
        <div >
            <a id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index-new-btn">&nbsp;</a>
            <a id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index-del-btn">&nbsp;</a>
        </div>
    </div>
    <div region="west" style="width:50%" split="true">
        <div id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index-dg"></div>
    </div>
    <div region="center">
        <div id="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index-pg"></div>
    </div>
</div>

<?='<?php $this->endBody();?>'."\n"?>
<?='<?php $this->endPage();?>'."\n"?>
<?="<?php\n"?>
<?='$this->registerJs(<<<EOD'."\n"?>
<?='  yii.'.Inflector::camel2id(StringHelper::basename($generator->modelClass))."Index.init();\n"?>
<?="EOD\n"?>
<?="    , View::POS_END );\n"?>
<?=StringHelper::basename($generator->modelClass).'IndexAsset::register($this);'?>