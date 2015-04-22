<?php
echo "<?php\n";
?>

use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'Dashboard';
?>

<?= '<?php $this->beginPage();?>'."\n" ?>
<?= '<?php $this->head();?>'."\n" ?>
<?= '<?php $this->beginBody();?>'."\n" ?>
<div id="dashboard-index">
    <?="This is dashboard page, change file at path <?=__FILE__?> for customize\n"?>
</div>
<?= '<?php $this->endBody();?>'."\n" ?>
<?= '<?php $this->endPage();?>'."\n" ?>

<?= "<?php\n" ?>
$url = Url::to('/',true);
$this->registerJs(<<<EOD
        if(typeof yii.app !== undefined ){
            //yii.siteIndex.init();
        }else{
            window.location = '{$url}';
        }
EOD
    , View::POS_END );

//SiteIndexAsset::register($this);