<?php

use yii\helpers\Html;
?>
<div id="nort-content">
    <!-- <div class="panel-icon">
        <a href="javascript:;" class="layout-button-buffer" onclick="yii.easyui.sideMenuToggle()">
            <svg focusable="false" viewBox="0 0 24 24">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </a>
    </div> -->
    <div class="panel-title panel-with-icon logo">
        <?php echo Yii::$app->name;?>
    </div>
    <div id="north-right-nav">
        <a id="north-user-menu-btn"></a>
        <div id="north-user-menu"></div>
    </div>
</div>