<?php
/** @var yii\web\View $this */

use sheillendra\jeasyui\assets\InitDataAsset;

InitDataAsset::register($this);
?>
<div id="initial-data-index" style="min-width: 1154px"></div>
<?php
$this->registerJs(<<<JS
    yii.easyui.tabInit = function(){
        yii.app.initialData.init();
        yii.easyui.hideMainMask();
    };
JS
        , $this::POS_END);