<?php

echo "<?php\n";

?>
use yii\helpers\Url;

$navItemUrl = [
<?php foreach ($navItemUrl as $k=>$v) : ?>
    <?= "'$k'=>[]"?>
<?php endforeach; ?>
];

$navItem = [
<?php foreach ($navItem as $k=>$v) : ?>
    <?= "'$k'=>[]"?>
<?php endforeach; ?>
];
