<?php

use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
?>

<?php $this->beginPage();?>
<?php $this->head();?>
<?php $this->beginBody();?>
<div id="setting-general-index">
    This is dashboard page, change file at path <?=__FILE__?> for customize
</div>
<?php $this->endBody();?>
<?php $this->endPage();?>

<?php
$url = Url::to('/',true);
$this->registerJs(<<<EOD
        if(typeof yii.app !== undefined ){
            //yii.siteIndex.init();
            yii.easyui.hideMainMask();
        }else{
            window.location = '{$url}';
        }
EOD
    , View::POS_END );

//SiteIndexAsset::register($this);