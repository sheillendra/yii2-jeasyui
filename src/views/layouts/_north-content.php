<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<div id="navbar">
    <input id="ss"></input>
    <div id="mm" style="width:120px">
        <div data-options="name:\'all\',iconCls:\'icon-ok\'">All News</div>
        <div data-options="name:\'sports\'">Sports News</div>
    </div>
    <div id="north-right-nav">
        <a id="north-user-menu-btn"></a>
        <div id="north-user-menu"></div>
    </div>
</div>

<?php
$this->registerJs(
    <<<JS
    yii.easyui.navbarInit = function(){

        var layerGroupNavbar = $('<div></div>').menubutton({
            iconCls: 'fa-solid fa-layer-group',
            plain: true,
            size: 'large',
            text: 'ERP',
            border: false
        });

        $('#navbar').prepend(layerGroupNavbar);

        $('#ss').searchbox({
            width: '50%',
            searcher:function(value,name){
                alert(value + "," + name)
            },
            menu:'#mm',
            prompt:'Please Input Value'
        });

    }
JS
);