<?php
/* @var $this yii\web\View */
?>

<div id="dashboard-index">
    This is dashboard page, change file at path <?= __FILE__ ?> for customize
</div>

<?php
$this->registerJs(<<<JS
    yii.easyui.hideMainMask();
JS
        , $this::POS_END);

//SiteIndexAsset::register($this);