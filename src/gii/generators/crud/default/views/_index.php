<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$baseName = str_replace('Ext', '', StringHelper::basename($generator->modelClass));
$humanizeBaseName = Inflector::humanize(Inflector::camel2id($baseName, '_'), true);
$varBaseName = Inflector::variablize($baseName);
$id = Inflector::camel2id($baseName);
$constId = strtoupper(Inflector::camel2id($baseName, '_'));

$model = new ($generator->modelClass);
if(method_exists($model, 'getEasyuiAttributes')){
    $easyuiAttributes = $model->easyuiAttributes;
} else {
    $easyuiAttributes = [];
}

$dgToolbar = [
    'create' => true,
    'update' => true,
    'delete' => true,
];

$extraDgToolbarText = '';
$extraDgToolbarReg = '';
if(isset($easyuiAttributes['_'])){
    if(isset($easyuiAttributes['_']['dgToolbar'])){
        foreach($easyuiAttributes['_']['dgToolbar'] as $k => $v){
            $dgToolbar[$k] = $v;
        }
    }

    if(isset($easyuiAttributes['_']['extraDgToolbar'])){
        foreach($easyuiAttributes['_']['extraDgToolbar'] as $k => $v){
            $idText = Inflector::camel2id($k);
            $extraDgToolbarText .= "            <?php\n";
            $extraDgToolbarText .= "            \$initTb{$k} = '';\n";
            $extraDgToolbarText .= "            if (Yii::\$app->user->can({$v['permission']})):\n";
            $extraDgToolbarText .= "                \$initTb{$k} = <<<JS\n";
            $extraDgToolbarText .= "                    $('#{$id}-tb-{$idText}').linkbutton({\n";
            $extraDgToolbarText .= "                        text: '{$v['label']}',\n";
            $extraDgToolbarText .= "                        iconCls: '{$v['icon']}',\n";
            $extraDgToolbarText .= "                        plain: true,\n";
            $extraDgToolbarText .= "                        onClick: function () {\n";
            $extraDgToolbarText .= "                            {$v['onclick']}\n";
            $extraDgToolbarText .= "                        },\n";
            $extraDgToolbarText .= "                    });\n";
            $extraDgToolbarText .= "JS\n";
            $extraDgToolbarText .= "            ?>\n";
            $extraDgToolbarText .= "                <td>\n";
            $extraDgToolbarText .= "                    <div id=\"{$id}-tb-{$idText}\"></div>\n";
            $extraDgToolbarText .= "                </td>\n";
            $extraDgToolbarText .= "            <?php endif; ?>\n";
            $extraDgToolbarReg .= "        {\$initTb{$k}}\n";
        }
    }
}

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
<?php if($dgToolbar['create']):?>
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
<?php endif;?>
<?php if($dgToolbar['update']):?>
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
<?php endif;?>
<?php if($dgToolbar['delete']):?>
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
<?php endif;?>
<?=$extraDgToolbarText?>
        </tr>
    </table>
</div>
<?="<?php\n"?>
$this->registerJs(
    <?="<<<JS\n"?>
    yii.easyui.tabInit = function(){
        yii.easyui.<?=$varBaseName?>.init();
<?php if($dgToolbar['create']):?>
        {$initTbCreate}
<?php endif;?>
<?php if($dgToolbar['update']):?>
        {$initTbUpdate}
<?php endif;?>
<?php if($dgToolbar['delete']):?>
        {$initTbDelete}
<?php endif;?>
<?=$extraDgToolbarReg?>
        yii.easyui.hideMainMask();
    };
JS,
    $this::POS_END
);
