<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<div style="padding-top: 5px">
    <div id="toggle-sidebar"></div>
    <div id="tools"></div>
    <div id="tools-menu"></div>
</div>

<?php
$this->registerJs(
    <<<JS
    yii.easyui.afterInit.push(()=>{

        $('#toggle-sidebar').linkbutton({
                iconCls: 'fa-solid fa-bars',
            plain: true,
            // size: 'large',
            onClick: function () {
                if (yii.easyui.cookie.get('west-collapsed') == 1) {
                    yii.easyui.appContainer.layout('expand', 'west');
                } else {
                    yii.easyui.appContainer.layout('collapse', 'west');
                }

            }
        });

        var toolsMenu = $('#tools-menu').menu({});

        toolsMenu.menu('appendItem', {
            text: 'Cleans Publish Asset',
            onclick: function () {
                yii.easyui.ajax.request({
                    data: {
                        r: '/jeasyui/tools/clean-assets',
                    },
                    host: 'backend',
                    type: 'GET',
                });
            }
        });

        toolsMenu.menu('appendItem', {
            text: 'Clear Schema Cache',
            onclick: function () {
                yii.easyui.ajax.request({
                    data: {
                        r: '/jeasyui/tools/clear-schema-cache',
                    },
                    host: 'backend',
                    type: 'GET',
                });
            }
        });

        toolsMenu.menu('appendItem', {
            text: 'Flush Cache',
            onclick: function () {
                yii.easyui.ajax.request({
                    data: {
                        r: '/jeasyui/tools/flush-cache',
                    },
                    host: 'backend',
                    type: 'GET',
                });
            }
        });

        toolsMenu.menu('appendItem', {
            text: 'Invalidate Dependency',
            onclick: function () {
                yii.easyui.ajax.request({
                    data: {
                        r: '/jeasyui/tools/invalidate-dependency',
                    },
                    host: 'backend',
                    type: 'GET',
                    prompt: 'Please enter tag target',
                    promptName: 'tag'
                });
            }
        });

        $('#tools').menubutton({
            //iconCls: 'fa-solid fa-bars',
            text: 'Tools',
            plain: true,
            menu: toolsMenu,
            showEvent: 'mousedown',
        });
    });
JS
);
