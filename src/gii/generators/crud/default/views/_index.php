<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
$humanizeBaseName = Inflector::humanize(Inflector::camel2id($baseName, '_'), true);
$varBaseName = Inflector::variablize($baseName);
$id = Inflector::camel2id($baseName);
$constId = strtoupper(Inflector::camel2id($baseName, '_'));
echo "<?php\n";
?>

/** @var $this yii\web\View */

use <?= $generator->appName ?>\models\User;
use <?= $generator->appName ?>\themes\jeasyui\assets\<?= $baseName ?>Asset;

<?= $baseName ?>Asset::register($this);
?>
<div id="<?=$id?>-index" style="min-width: 1154px"></div>
<div id="<?=$id?>-tb" class="datagrid-toolbar">
    <table cellspacing="0" cellpadding="0">
        <tr>
            <?="<?php\n"?>
            $initTbCreate = '';
            if (Yii::$app->user->can(User::CREATE_<?=$constId?>_PERMISSION)):
                <?='$initTbCreate = <<<JS'?><?="\n"?>
                    $('#<?=$id?>-tb-create').linkbutton({
                        text: 'New',
                        iconCls: 'icon-add',
                        plain: true,
                        onClick: function () {
                            yii.easyui.<?=$varBaseName?>.frmDlg();
                        },
                    });
JS;
            ?>
                <td>
                    <div id="<?=$id?>-tb-create"></div>
                </td>
            <?="<?php endif; ?>\n"?>
            <?="<?php\n"?>
            $initTbUpdate = '';
            if (Yii::$app->user->can(User::UPDATE_<?=$constId?>_PERMISSION)):
                <?='$initTbUpdate = <<<JS'?><?="\n"?>
                    $('#<?=$id?>-tb-update').linkbutton({
                        text: 'Edit',
                        iconCls: 'icon-edit',
                        plain: true,
                        onClick: function () {
                            if(!yii.easyui.<?=$varBaseName?>.dgRow){
                                    return $.messager.alert('Edit <?=$humanizeBaseName?>', 'Select data to edit.', 'error');
                            }
                            yii.easyui.<?=$varBaseName?>.frmDlg(yii.easyui.<?=$varBaseName?>.dgRow);
                        },
                    });
JS;
            ?>
                <td>
                    <div id="<?=$id?>-tb-update"></div>
                </td>
            <?="<?php endif; ?>\n"?>
            <?="<?php\n"?>
            $initTbDelete = '';
            if (Yii::$app->user->can(User::DELETE_<?=$constId?>_PERMISSION)):
                <?='$initTbDelete = <<<JS'?><?="\n"?>
                    $('#<?=$id?>-tb-delete').linkbutton({
                        text: 'Delete',
                        iconCls: 'icon-remove',
                        plain: true,
                        onClick: function () {
                            if(!yii.easyui.<?=$varBaseName?>.dgRow){
                                return $.messager.alert('Delete <?=$humanizeBaseName?>', 'Select data to delete.', 'error');
                            }
                            yii.easyui.ajax.request({
                                id: yii.easyui.<?=$varBaseName?>.dgRow.id,
                                route: 'api/v1/<?=$id?>/delete',
                                text: '<?=$humanizeBaseName?>',
                                type: 'DELETE',
                                callback: () => {
                                    yii.easyui.<?=$varBaseName?>.dg.datagrid('reload');
                                }
                            });
                        }
                    });
JS;
                ?>
                <td>
                    <div class="datagrid-btn-separator"></div>
                </td>
                <td>
                    <div id="<?=$id?>-tb-delete"></div>
                </td>
            <?="<?php endif; ?>\n"?>
        </tr>
    </table>
</div>
<?="<?php\n"?>
$this->registerJs(
    <?="<<<JS\n"?>
    yii.easyui.tabInit = function(){
        yii.easyui.<?=$varBaseName?>.init();
        {$initTbCreate}
        {$initTbUpdate}
        {$initTbDelete}
        yii.easyui.hideMainMask();
    };
JS,
    $this::POS_END
);
