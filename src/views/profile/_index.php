<?php
/* @var $this yii\web\View */

use sheillendra\jeasyui\assets\ProfileAsset;

ProfileAsset::register($this);
?>
<div id="profile-index" style="min-width: 1154px"></div>
<?php
$this->registerJs(<<<JS
    yii.easyui.tabInit = function(){
        yii.profile.init();
        yii.easyui.hideMainMask();
    };
JS
        , $this::POS_END);
