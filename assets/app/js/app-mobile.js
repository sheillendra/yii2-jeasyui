var yii = yii || {};
yii.app = (function ($) {
    var ajaxDialogForm = {};
    var maintab = {};
    var mainMask = $('.main-mask');
    var mainMaskTask = 0;
    var doIfReferenceExists = function (arrReference, callback, param) {
        var newArrReference = [];
        var i = 1;
        $.each(arrReference, function (k, v) {
            if (i) {
                i = 0;
            } else {
                newArrReference.push(v);
            }
        });
        if (newArrReference.length) {
            yii.app.doIfReferenceExists(newArrReference, callback, param);
        } else {
            callback(param);
        }
    };
    var globalError = $('#global-error');

    return {
        isActive: false,
        westContent: '',
        northContent: '',
        centerContent: '',
        southContent: '',
        reference: {},
        init: function () {

            $.ajaxSetup({
                error: function (e) {
                    yii.app.showError(e);
                }
            });

            using(['panel', 'mobile', 'accordion', 'layout', 'menu', 'menubutton', 'linkbutton', 'tabs', 'messager'], function () {

                $('body').navpanel();

                if (typeof yii.app.errors !== 'undefined') {
                    $.messager.alert(yii.app.errors.name, yii.app.errors.message, 'error');
                }
                delete yii.app.errors;
            });
        },
        createTab: function (title, url, icon, nav) {
            if (maintab.tabs('exists', title)) {
                //hide dari click accordion
                yii.app.hideMainMask();
                maintab.tabs('select', title);
            } else {
                maintab.tabs('add', {
                    title: title,
                    href: url,
                    closable: true,
                    iconCls: icon,
                    data: {nav: nav, url: url}
                });
            }
        },
        showGridMsg: function (target, data) {
            var vc = $(target).datagrid('getPanel').children('div.datagrid-view');
            if (!data.rows.length) {
                var d = $('<div class="datagrid-empty"></div>').html('No Records Found').appendTo(vc);
                d.css({
                    position: 'absolute',
                    left: 0,
                    top: 50,
                    width: '100%',
                    textAlign: 'center'
                });
            } else {
                vc.children('div.datagrid-empty').remove();
            }
        },
        showError: function (e) {
            var content = typeof e.responseText !== 'undefined' ? e.responseText : e;
            if (typeof content === 'object') {
                var temp = [];
                $.each(content, function (k, v) {
                    temp.push(v);
                });
                content = temp.join(', ');
            }
            using('dialog', function () {
                globalError.dialog({
                    title: 'Error',
                    modal: true,
                    minWidth: 300,
                    minHeight: 200,
                    maxWidth: window.innerWidth - 50,
                    maxHeight: window.innerHeight - 50,
                    content: content
                });
            });
        },
        doIfReferenceExists: function (arrReference, callback, param) {
            if (typeof arrReference[0] !== 'undefined' && typeof yii.app.reference[arrReference[0]] === 'undefined') {
                $.ajax({
                    url: yii.app.getReferenceUrl,
                    data: {type: arrReference[0]},
                    dataType: 'json',
                    success: function (r) {
                        yii.app.reference[arrReference[0]] = r;
                        doIfReferenceExists(arrReference, callback, param);
                    }
                });
            } else {
                doIfReferenceExists(arrReference, callback, param);
            }
        },
        dateBoxFormatter: function (date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();
            //return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
        },
        dateBoxParser: function (s) {
            if (!s)
                return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0], 10);
            var m = parseInt(ss[1], 10);
            var d = parseInt(ss[2], 10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                return new Date(d, m - 1, y);
            } else {
                return new Date();
            }
        },
        //ini digunakan ketika onLoad di set di module pemanggil
        formOptionsOnCreateAjaxDialogForm: {},
        /**
         * 
         * @param {string} moduleName
         * @param {string} formName
         * @param {object} dialogOptions
         * @param {object} formOptions
         * @returns {boolean}
         */
        createAjaxDialogForm: function (moduleName, formName, dialogOptions, formOptions) {
            formOptions = formOptions || {};

            /*
             * jika onLoad di defaultOption di override di module pemanggil
             * onLoad di fungsi ini harus tetap dijalankan
             */
            yii.app.formOptionsOnCreateAjaxDialogForm[formName] = formOptions;

            if (typeof ajaxDialogForm[moduleName] === 'undefined') {
                ajaxDialogForm[moduleName] = {};
            }

            if (typeof ajaxDialogForm[moduleName][formName] === 'undefined') {
                var defaultDialogOption = {
                    title: formName,
                    closed: true,
                    modal: true,
                    width: 500,
                    height: 400,
                    buttons: [
                        {
                            iconCls: 'icon-disk',
                            text: 'Save',
                            handler: function () {
                            }
                        },
                        {
                            iconCls: 'icon-cancel',
                            text: 'Cancel',
                            handler: function () {
                                ajaxDialogForm[moduleName][formName].dialog('close');
                            }
                        }
                    ],
                    onLoad: function () {
                        if (typeof formOptions !== 'undefined' && typeof formOptions.loadData !== 'undefined') {
                            $('#' + formName).form('load', formOptions.loadData);
                        }
                    }
                };

                dialogOptions = $.extend(true, defaultDialogOption, dialogOptions);

                if (window.innerHeight - 20 < dialogOptions.height) {
                    dialogOptions.height = window.innerHeight - 20;
                }
                using('dialog', function () {
                    ajaxDialogForm[moduleName][formName] = $('<div></div>').dialog(dialogOptions);
                });
            } else {
                using('form', function () {
                    ajaxDialogForm[moduleName][formName].children().form('clear');
                    if (typeof formOptions !== 'undefined' && typeof formOptions.loadData !== 'undefined') {
                        ajaxDialogForm[moduleName][formName].children().form('load', formOptions.loadData);
                    }
                    yii.refreshCsrfToken();
                });
            }

            ajaxDialogForm[moduleName][formName].dialog('open');

            return !0;
        },
        getDialogForm: function (module, formName) {
            if (typeof ajaxDialogForm[module] !== 'undefined' && typeof ajaxDialogForm[module][formName] !== 'undefined') {
                return ajaxDialogForm[module][formName];
            } else {
                return $('#blank');
            }
        },
        destroyDialogForm : function(module, formName){
            if (typeof ajaxDialogForm[module] !== 'undefined' && typeof ajaxDialogForm[module][formName] !== 'undefined') {
                yii.app.getDialogForm(module, formName).dialog('destroy');
                delete ajaxDialogForm[module][formName];
            }
        },
        deleteHandler: function (dg, url, pk, arrPk, callbacks) {
            var idCheked = dg.datagrid('getChecked');
            if (idCheked.length) {
                using('messager', function () {
                    $.messager.confirm('Delete Confirmation', 'Are you sure to delete the checked rows?', function (response) {
                        if (response) {
                            pk = pk || 'id';
                            arrPk = arrPk || 'ids';
                            callbacks = callbacks || {};
                            callbacks.doAfterDelete = callbacks.doAfterDelete || function (ajaxResponse) {
                                if (ajaxResponse.success) {
                                    if (typeof ajaxResponse.list !== 'undefined') {
                                        dg.datagrid('loadData', ajaxResponse.list);
                                    } else {
                                        dg.datagrid('reload');
                                    }
                                } else {
                                    if (typeof ajaxResponse.error !== 'undefined') {
                                        yii.app.showError(ajaxResponse.error);
                                    } else {
                                        $.messager.alert(ajaxResponse.name, ajaxResponse.message, 'error');
                                    }
                                }
                            };

                            var data = {};
                            var options = dg.datagrid('options');

                            data.rows = options.pageSize;
                            data.page = options.pageNumber;

                            data[arrPk] = [];

                            $.each(idCheked, function (k, v) {
                                data[arrPk].push(v[pk]);
                            });

                            if (typeof callbacks.doBeforeDelete !== 'undefined') {
                                callbacks.doBeforeDelete(data);
                            }

                            $.ajax({
                                url: url,
                                type: 'post',
                                dataType: 'json',
                                data: data,
                                success: function (r) {
                                    yii.refreshCsrfToken();
                                    callbacks.doAfterDelete(r);
                                }
                            });
                        }
                    }
                    );
                });
            } else {
                $.messager.alert('Attention', 'Must checked the record will be delete', 'warning');
            }
        },
        defaults: {
            validatebox: {
                minLength: function () {
                    using('validatebox', function () {
                        if (typeof $.fn.validatebox.defaults.rules.minLength === 'undefined') {
                            $.fn.validatebox.defaults.rules.minLength = {
                                validator: function (value, param) {
                                    return value.length >= param[0];
                                },
                                message: 'Please enter at least {0} characters.'
                            };
                        }
                    });
                },
                equals: function () {
                    using('validatebox', function () {
                        if (typeof $.fn.validatebox.defaults.rules.equals === 'undefined') {
                            $.fn.validatebox.defaults.rules.equals = {
                                validator: function (value, param) {
                                    return value === $(param[0]).val();
                                },
                                message: 'Field do not match.'
                            };
                        }
                    });
                }
            },
            pagination: {
                displayMsg: function (msg) {
                    msg = msg || '{from} - {to} of {total}';
                    if (msg === 'reset') {
                        msg = 'Displaying {from} to {to} of {total} items';
                    }
                    using('pagination', function () {
                        $.fn.pagination.defaults.displayMsg = msg;
                    });
                },
                last: function () {
                    using('pagination', function () {
                        $.fn.pagination.defaults.last = {};
                    });
                }
            }
        },
        getMainTab: function () {
            return maintab;
        },
        defaultDgOptions: {
            fit: !0,
            striped: !0,
            border: !1,
            method: 'get',
            rownumbers: !0,
            pagination: !0,
            checkOnSelect: !1,
            selectOnCheck: !1,
            singleSelect: !0,
            onLoadSuccess: function (data) {
                yii.app.showGridMsg(this, data);
                $(this).datagrid('selectRow', 0);
                yii.app.hideMainMask();
            },
            onLoadError: function (e) {
                yii.app.showError(e);
                yii.app.hideMainMask();
            }
        },
        showMainMask: function () {
            mainMaskTask++;
            if (mainMaskTask === 1) {
                mainMask.css('display', 'block');
            }
        },
        hideMainMask: function () {
            if (mainMaskTask > 0) {
                mainMaskTask--;
            }
            if (mainMaskTask === 0) {
                mainMask.css('display', 'none');
            }
        },
        cookie: {
            set: function (name, value, days, path) {
                days = days || 1;
                path = path || '';
                var expires = '';
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toGMTString();
                }
                document.cookie = name + "=" + value + expires + "; path=/" + path;
            },
            get: function (name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) === ' ')
                        c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0)
                        return c.substring(nameEQ.length, c.length);
                }
                return null;
            },
            delete: function (name, path) {
                yii.app.cookie.set(name, "", -1, path);
            }
        }
    };
})(jQuery);