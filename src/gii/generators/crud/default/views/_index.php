<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
$varBaseName = Inflector::variablize($baseName);
echo "<?php\n";
?>

/** @var $this yii\web\View */

use backend\themes\jeasyui\assets\<?= $baseName ?>Asset;

<?= $baseName ?>Asset::register($this);
?>
<div id="<?=Inflector::camel2id($baseName)?>-index" style="min-width: 1154px"></div>
<?="<?php\n"?>
$this->registerJs(
    <?="<<<JS\n"?>
    yii.easyui.tabInit = function(){
        yii.app.<?=$varBaseName?>.init();
        yii.easyui.hideMainMask();
    };
JS,
    $this::POS_END
);
