<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<div id="navbar">
    <div id="layer-btn"></div>
    <div id="layer-menu" style="display: none;">
        <div data-options="name:\'app1\',iconCls:\'fa-solid fa-check\'">APP 1</div>
        <div data-options="name:\'app2\'">APP 2</div>
        <div data-options="name:\'app3\'">APP 3</div>
    </div>
    <div class="panel-title" style="display: inline;margin-right: 100px;height:45px">CORPORATE</div>
    <div style="display: inline;width: 300px;"><input id="navbar-search-box" style="display: none;"></input></div>
    <div id="north-right-nav">
        <div id="mm" style="width:120px;display:none;">
            <div data-options="name:\'all\',iconCls:\'fa-solid fa-check\'">All</div>
            <div data-options="name:\'post\'">Post</div>
            <div data-options="name:\'user\'">User</div>
        </div>
        <div id="notification-btn"></div>
        <a id="north-user-menu-btn"></a>
        <div id="north-user-menu">
            
        </div>
    </div>
</div>

<?php
$this->registerJs(
    <<<JS
    yii.easyui.afterInit.push(()=>{

        $('#layer-btn').menubutton({
            iconCls: 'fa-solid fa-layer-group',
            plain: true,
            size: 'large',
            menu: '#layer-menu',
            border: false,
            showEvent: 'mousedown',
            hasDownArrow: false
        });

        $('#notification-drawer').drawer();
        $('#notification-btn').linkbutton({
            iconCls: 'fa-solid fa-bell',
            plain: true,
            size: 'large',
            border: false,
            onClick: function(){
                $('#notification-drawer').drawer('expand');
            }
        });

        $('#navbar-search-box').searchbox({
            width: 400,
            searcher:function(value,name){
                alert(value + "," + name)
            },
            menu:'#mm',
            prompt:'Search'
        });

        northUserMenu = $('#north-user-menu');
        northUserMenu.menu({});
        var parentItem = {};
        $.each(yii.easyui.northUserMenu, function (k, v) {
            if (v.parent !== undefined) {
                if (parentItem[v.parent] === undefined) {
                    parentItem[v.parent] = northUserMenu.menu('findItem', v.parent);
                }
                if (parentItem[v.parent]) {
                    v.parent = parentItem[v.parent].target;
                } else {
                    return false;
                }
            }
            northUserMenu.menu('appendItem', v);
        });

        $('#north-user-menu-btn').menubutton({
            text: yii.easyui.username,
            iconCls: 'fa-solid fa-user',
            menu: northUserMenu,
            menuAlign: 'right',
            showEvent: 'mousedown',
            size: 'large',
            hasDownArrow: false
        });

    });
JS
);