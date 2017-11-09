<?php

use yii\helpers\Html;

/* @var $model backend\modules\user\models\LoginForm */
?>
<?php echo Html::beginForm(['/easyui/login'], 'post', ['id' => 'login-form']) ?>
<div style="padding:0 20px">
    <div style="margin-bottom:10px">
        <!--<input class="easyui-textbox" data-options="prompt:'Type username',iconCls:'icon-man'" style="width:100%;height:38px">-->
        <?php echo Html::activeTextInput($model, 'username', ['class' => "easyui-textbox", 'data-options' => "prompt:'Type username',iconCls:'icon-man'"]) ?>
    </div>
    <div>
        <!--<input class="easyui-passwordbox" data-options="prompt:'Type password'" style="width:100%;height:38px">-->
        <?php echo Html::activeTextInput($model, 'password') ?>
    </div>
    <div style="text-align:center;margin-top:30px">
        <a href="#" class="easyui-linkbutton" style="width:100%;height:40px"><span style="font-size:16px">Login</span></a>
    </div>
    <div style="text-align:center;margin-top:30px">
        <a href="#" class="easyui-linkbutton" plain="true" outline="true" style="width:100px;height:35px"><span style="font-size:16px">Register</span></a> 
    </div>
</div>
<?php
echo Html::endForm();
