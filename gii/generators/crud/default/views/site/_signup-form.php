<?php
echo "<?php\n";
?>
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= "<?php echo Html::beginForm('/user/signup', '', ['id' => 'signup-form']) ?>\n" ?>
    <table>
        <tbody>
            <tr>
                <?= '<td width="80px"><?= Html::activeLabel($model, \'username\')?></td>'."\n" ?>
                <td>:</td>
                <?= '<td><?= Html::activeTextInput($model, \'username\')?></td>'."\n" ?>
            </tr>
            <tr>
                <?= '<td width="80px"><?= Html::activeLabel($model, \'email\')?></td>'."\n" ?>
                <td>:</td>
                <?= '<td><?= Html::activeTextInput($model, \'email\')?></td>'."\n" ?>
            </tr>
            <tr>
                <?= '<td width="80px"><?= Html::activeLabel($model, \'password\')?></td>'."\n" ?>
                <td>:</td>
                <?= '<td><?= Html::activePasswordInput($model, \'password\')?></td>'."\n" ?>
            </tr>
        </tbody>
    </table>

<?= "<?php echo Html::endForm()?>\n";
