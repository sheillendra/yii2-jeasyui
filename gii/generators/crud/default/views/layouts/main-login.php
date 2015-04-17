<?php

echo "<?php\n";

?>
use yii\helpers\Html;
use sheillendra\jeasyui\jEasyUIAsset;

/* @var $this \yii\web\View */
/* @var $content string */

jEasyUIAsset::register($this);
?>
<?= '<?php $this->beginPage() ?>'."\n" ?>
<!DOCTYPE html>
<?= '<html lang="<?= Yii::$app->language ?>">'."\n" ?>
    <head>
        <?= '<meta charset="<?= Yii::$app->charset ?>">'."\n" ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= '<?= Html::csrfMetaTags() ?>'."\n" ?>
        <?= '<title><?= Html::encode($this->title) ?></title>'."\n" ?>
        <?= '<?php $this->head() ?>'."\n" ?>
    </head>
    <body>
        <div id="error"></div>
        <?= '<?php $this->beginBody() ?>'."\n" ?>
        <?= '<?= $content ?>'."\n" ?>
        <?= '<?php $this->endBody() ?>'."\n" ?>
    </body>
</html>
<?= '<?php $this->endPage() ?>'."\n" ?>
