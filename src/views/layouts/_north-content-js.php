<?php

/* @var $this \yii\web\View */

$this->registerJs(
    <<<JS
    yii.easyui.afterInitAppLayout.push(()=>{

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

        toolsMenu.menu('appendItem', {
            text: 'Camera Permission',
            onclick: function () {
                async function mintaIzinKamera() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: "environment" }
                        });
                        stream.getTracks().forEach(track => track.stop());
                    } catch (err) {
                        console.error("Gagal akses kamera:", err);
                        
                    }
                }

                mintaIzinKamera();
            }
        });

        $('#tools').menubutton({
            //iconCls: 'fa-solid fa-bars',
            text: 'Tools',
            plain: true,
            menu: toolsMenu,
            showEvent: 'mousedown',
        });

        var rbacMenu = $('#rbac-menu').menu({});

        rbacMenu.menu('appendItem', {
            text: 'Reset Default RBAC',
            onclick: function () {
                yii.easyui.ajax.request({
                    data: {
                        r: 'jeasyui/api/user/reset-rbac',
                    },
                    host: 'it',
                    type: 'GET',
                    callback: function(){
                        window.location.reload();
                    }
                });
            }
        });

        $('#rbac').menubutton({
            //iconCls: 'fa-solid fa-bars',
            text: 'RBAC',
            plain: true,
            menu: rbacMenu,
            showEvent: 'mousedown',
        });
    });
JS
);
