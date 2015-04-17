<?php

echo "<?php\n";

?>

use app\assets\AppAsset;
use yii\helpers\Html;
use app\components\helpers\Regex;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
<?= "?>\n" ?>
<?= '<?php $this->beginPage() ?>'."\n";?>
<!DOCTYPE html>
<?= '<html lang="<?= Yii::$app->language ?>">'."\n" ?>
    <head>
        <?= '<meta charset="<?= Yii::$app->charset ?>">'."\n" ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= "<?= Html::csrfMetaTags() ?>\n" ?>
        <?= '<title><?= Html::encode($this->title) ?></title>'."\n" ?>
        <?= '<?php $this->head() ?>'."\n" ?>
    </head>
    <body>
        <div id="global-error"></div>
        <?= '<?php $this->beginBody() ?>'."\n"?>

        <?= '<?php $this->endBody() ?>'."\n"?>
    </body>
</html>

<?= "<?php\n" ?>
$username = Yii::$app->user->identity->username;
$logoutUrl = Url::to('/site/logout',true);
$northContent = preg_replace(Regex::htmlMinified, ' ', $this->render('_north-content'));
$centerContent = '<div id="maintab"></div>';
$westContent = preg_replace(Regex::htmlMinified, ' ', $this->render('_west-content'));

$params = $this->params;

require(__DIR__ . '/_nav-item.php');

$this->params['selectedNavAccordion'] = isset($this->params['selectedNavAccordion']) ? $this->params['selectedNavAccordion'] : 'dashboard';

$navItem[$this->params['selectedNavAccordion']]['selected'] = true;

$navItem = Json::encode($navItem);

$this->registerJs(<<<EOD
    yii.app.logoutUrl = '{$logoutUrl}';
    yii.app.northContent = '{$northContent}';
    yii.app.centerContent = '{$centerContent}';
    yii.app.westContent = '{$westContent}';
    yii.app.navItem = {$navItem};
    yii.app.selectedNav = '{$this->params['selectedNav']}';
    yii.app.init();
EOD
<?= ");?>\n"?>

<?= '<?php $this->endPage(); ?>'."\n"?>
