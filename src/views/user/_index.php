<?php
/* @var $this yii\web\View */

use sheillendra\jeasyui\assets\UserAsset;
use yii\helpers\Json;

UserAsset::register($this);
?>
<div id="user-index" style="min-width: 1154px"></div>
<div id="user-tb" class="datagrid-toolbar">
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div id="user-tb-new"></div>
            </td>
            <td>
                <div id="user-tb-edit"></div>
            </td>
            <td>
                <div id="user-tb-reset"></div>
            </td>
            <td>
                <div class="datagrid-btn-separator"></div>
            </td>
            <td>
                <div id="user-tb-hapus"></div>
            </td>
            <td>
                <div id="user-tb-assignment"></div>
                <div id="user-tb-assignment-mm" style="width:150px;"></div>
            </td>
        </tr>
    </table>
</div>
<?php
$roles = Json::encode(Yii::$app->user->identity->authItemCanManage);
$this->registerJs(
    <<<JS
    yii.easyui.tabInit = function(){
        yii.easyui.user.roles = {$roles};
        yii.easyui.user.apiRoute = 'api/v1'
        yii.easyui.user.init();
        yii.easyui.hideMainMask();
    };
JS,
    $this::POS_END
);
