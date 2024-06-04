window.yii.app = (($) => {

    var ajaxQueueCallback = {
        satker: [],
        jabatanDaerah: [],
        jabatanStruktural: [],
        eselon: [],
        pangkat: [],
        kategoriBiayaSppd: [],
        jenisBiayaSppd: [],
    };

    /**
     * 
     */
    var ajaxOnProcess = {};

    var refAjax = function (callback, refName, data = {}, idName = 'id', textName = 'nama') {
        ajaxOnProcess[refName] = 1;
        $.ajax({
            //url: yii.easyui.getHost('api'),

            //demo purpose
            url: yii.easyui.getHost('api') + '/' + data.r,

            data: yii.easyui.ajaxAuthToken(data),
            dataType: 'json',
            success: function (res) {
                yii.app.ref[refName] = {};
                yii.app.refResultById[refName] = {};
                $.each(res, function (k, v) {
                    yii.app.ref[refName][v[idName]] = v[textName];
                    yii.app.refResultById[refName][v[idName]] = v;
                });
                yii.app.refResult[refName] = res;
                callback(yii.app.ref[refName], res);

                if (ajaxQueueCallback[refName] && ajaxQueueCallback[refName].length) {
                    $.each(ajaxQueueCallback[refName], function (k, v) {
                        v(yii.app.ref[refName], res);
                    });
                }
                ajaxOnProcess[refName] = 0;
            },
            error: function (xhr) {
                return yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        refAjax(callback, refName, route, fields);
                    } else {
                        ajaxOnProcess[refName] = 0;
                    }
                });
            }
        });
    };

    var ajaxMap = {
        pangkat: (callback) => {
            //return refAjax(callback, 'pangkat', { r: 'v1/pangkat', fields: 'id, singkatan', 'per-page': 50 }, 'id', '//singkatan');
            
            //demo purpose
            return refAjax(callback, 'pangkat', { r: 'ref-pangkat.json', fields: 'id, singkatan', 'per-page': 50 }, 'id', '//singkatan');
        },
        satker: (callback) => {
            //return refAjax(callback, 'satker', { r: 'v1/satker', fields: 'id, nama', 'per-page': 50 }, 'id', 'nama');
            
            //demo purpose
            return refSatker;
        },
    };

    var getRef = function (refName, key, callback = () => { }) {
        if (!key) {
            return '';
        }
        if (yii.app.ref[refName]) {
            return yii.app.ref[refName][key];
        } else {
            if (ajaxMap[refName]) {
                if (ajaxOnProcess[refName]) {
                    ajaxQueueCallback[refName].push(callback);
                } else {
                    ajaxMap[refName](callback);
                }
            }
            return '';
        }
    };

    var itemRefDropdown = function (refName, options) {
        options = $.extend({
            valueField: 'id',
            textField: 'text',
        }, options);

        var res = [];
        $.each(yii.app.ref[refName], function (k, v) {
            var item = {};
            item[options.valueField] = k;
            item[options.textField] = v;
            res.push(item);
        });
        options.data = res;
        return options;
    };

    var renderRefDropdown = function (refName, element, callback = function () { }, options) {
        options = $.extend({
            width: 300,
            label: refName,
            labelPosition: 'top'
        }, options);

        element.combobox(itemRefDropdown(refName, options));
        callback();
    };

    var refDropdown = function (refName, element, callback, options = {}) {
        if (yii.app.ref[refName]) {
            renderRefDropdown(refName, element, callback, options);
        } else {
            ajaxMap[refName](function () {
                renderRefDropdown(refName, element, callback, options);
            });
        }
    };

    var prepareEqualDate = function (value, param) {
        var d1;
        if (param[0] && yii.app.dateForCompare[param[0]] !== undefined) {
            if (yii.app.dateForCompare[param[0]]) {
                d1 = $.fn.datebox.defaults.parser(yii.app.dateForCompare[param[0]]);
                param[0] = yii.app.dateForCompare[param[0]];
            } else {
                return true;
            }
        } else {
            d1 = $.fn.datebox.defaults.parser(param[0]);
            if (!param[0]) {
                param[0] = $.fn.datebox.defaults.formatter(d1);
            }
        }

        var d2 = $.fn.datebox.defaults.parser(value);
        if (!param[0]) {
            param[0] = $.fn.datebox.defaults.formatter(d1);
        }
        return {
            d1: d1,
            d2: d2
        };
    };

    var createTreeData = (res) => {
        yii.app.ref.satkerTreeList = [];
        var mapItems = {};
        $.each(res, (k, v) => {
            var item = {
                id: v.kode,
                text: v.nama,
                children: []
            };

            if (v.kode_induk === null) {
                yii.app.ref.satkerTreeList.push(item);
                mapItems[v.kode] = yii.app.ref.satkerTreeList[yii.app.ref.satkerTreeList.length - 1];
            } else {
                mapItems[v.kode_induk].children.push(item);
                mapItems[v.kode] = mapItems[v.kode_induk].children[mapItems[v.kode_induk].children.length - 1];
            }
        });
    };

    var satkerLoader = (params, success, error) => {
        if (yii.app.ref.satkerTreeList) {
            /** JSON parse dan stringify ada cara clone array nested */
            success(JSON.parse(JSON.stringify(yii.app.ref.satkerTreeList)));
        } else {
            if (yii.app.refResult['satker']) {
                createTreeData(yii.app.refResult['satker']);
                success(JSON.parse(JSON.stringify(yii.app.ref.satkerTreeList)));
            } else {
                if (ajaxOnProcess['satker']) {
                    ajaxQueueCallback['satker'].push(() => {
                        success(JSON.parse(JSON.stringify(yii.app.ref.satkerTreeList)));
                    });
                } else {
                    ajaxMap.satker((refData, res) => {
                        createTreeData(res);
                        success(JSON.parse(JSON.stringify(yii.app.ref.satkerTreeList)));
                    });
                }
            }
        }
    };

    var anggaranDropdownOptions = () => {
        return {
            url: yii.easyui.getHost('api'),
            queryParams: yii.easyui.ajaxAuthToken({
                r: 'v1/anggaran',
                //fields: 'id, nama'
            }),
            method: 'get',
            mode: 'remote',
            valueField: 'id',
            textField: 'kode_rekening',
            groupField: 'group',
            formatter: function (row) {
                var s = '<span style="font-weight:bold">' + row.kode_rekening + '</span><br/>' +
                    '<span style="color:#888">' + yii.easyui.currencyFormatter(row.saldo) + '</span>';
                return s;
            },
            onLoadSuccess: (data) => {
                yii.app.ref.anggaran = {};
                $.each(data, (k, v) => {
                    yii.app.ref.anggaran[v.id] = v;
                });
            }
        };
    };

    var ajaxDelete = (options) => {
        yii.easyui.showMainMask();
        $.ajax({
            url: yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({
                r: options.route,
                id: options.id
            }, true),
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                yii.easyui.hideMainMask();
                $.messager.alert('Hapus ' + options.text, 'Hapus ' + options.text + ' berasil', 'info');
                if (typeof options.callback === 'function') {
                    options.callback();
                }
            },
            error: function (xhr) {
                yii.easyui.hideMainMask();
                return yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        ajaxDelete(options);
                    }
                });
            }
        });
    };

    var docDlg;
    var generateDoc;
    var uploadDoc;
    var initDocDlg = (elementName, row, typePdf, force = false, canGenerate = true, canUpload = false) => {
        let title = row.nomor;
        let content = '<iframe src="' + row[typePdf] + (force ? ('&force=1&_= ' + Date.now()) : '') + '" style="width: 100%;height:100%;border:none;vertical-align:bottom" />';
        if (docDlg) {
            docDlg.dialog({ content: content });
            generateDoc.linkbutton({
                onClick: () => {
                    docDlg.dialog('close');
                    initDocDlg(elementName, row, typePdf, true);
                }
            });
            docDlg.dialog('open');
        } else {
            docDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: '95%',
                width: '95%',
                content: content,
                toolbar: '#' + elementName + '-doc-tb',
            });

            generateDoc = $('#' + elementName + '-doc-tb-generate');
            generateDoc.linkbutton({
                text: 'Generate Ulang',
                iconCls: 'icon-reload',
                plain: true,
                onClick: () => {
                    docDlg.dialog('close');
                    initDocDlg(elementName, row, typePdf, true);
                }
            });
        }
    };

    return {
        isActive: false,
        ref: {
            status: { 0: 'Not Active', 1: 'Active' },
            statusUser: { 0: 'Deleted', 9: 'Not Active', 10: 'Active' },
            levelWilayah: { 1: 'Mabes', 2: 'Polda', 3: 'Polres', 4: 'Polsek' },
        },
        refResult: {},
        refResultById: {},
        getRef: function (refName, key, callback) {
            return getRef(refName, key, callback);
        },
        refDropdown: function (refName, element, callback, options = {}) {
            return refDropdown(refName, element, callback, options);
        },

        personelDropdown: function (element, options = {}) {
            return element.combobox($.extend({
                url: yii.easyui.getHost('api'),
                queryParams: yii.easyui.ajaxAuthToken({
                    r: 'v1/personel',
                    fields: 'id, nama_tanpa_gelar'
                }),
                method: 'get',
                mode: 'remote',
                valueField: 'id',
                textField: 'nama_tanpa_gelar',
                width: 300,
                label: 'Pegawai',
                labelPosition: 'top',
            }, options));
        },
        
        satkerDropdown: function (element, options = {}, callback = function () { }) {
            return element.combotree($.extend({
                width: 300,
                label: 'Satker',
                labelPosition: 'top',
                loader: satkerLoader
            }, options));
        },

        /**
         * 
         * @param {Object} data
         * @param {Function} callback
         * @return {Boolean}
         */
        renderChart: function (data, successCallback, errorCallback) {
            errorCallback = errorCallback || function () { };
            $.ajax({
                url: yii.easyui.getHost('api'),
                data: yii.easyui.ajaxAuthToken(data),
                dataType: 'json',
                success: function (res) {
                    successCallback(res);
                },
                error: function (xhr) {
                    errorCallback(xhr);
                }
            });
            return false;
        },
        dateForCompare: {},
        validator: {
            dateLessDayFromNow: (days) => {
                return function (date) {
                    var now = new Date();
                    var d1 = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    var d2 = new Date(now.getFullYear(), now.getMonth(), now.getDate() - days);
                    return d2 < date && date <= d1;
                };
            }
        },
        editor: {
            readOnly: {
                type: 'textbox',
                options: {
                    readonly: true,
                    multiline: false
                }
            },
            textbox: (options) => {
                return {
                    type: 'textbox',
                    options: $.extend({
                        multiline: false,
                    }, options)
                };
            },
            textarea: (options) => {
                return {
                    type: 'textbox',
                    options: $.extend({
                        multiline: true,
                        height: 40,
                    }, options)
                };
            },
            ref: (refName, options, ajaxCallback = () => { }) => {
                if (yii.app.ref[refName]) {
                    return {
                        type: 'combobox',
                        options: itemRefDropdown(refName, options)
                    };
                } else {
                    ajaxMap[refName](() => {
                        ajaxCallback();
                    });
                }
            },
            satker: (options = {}) => {
                return {
                    type: 'combotree',
                    options: {
                        panelMinWidth: 250,
                        loader: satkerLoader
                    },
                };
            },
            anggaran: (options = {}) => {
                return {
                    type: 'combobox',
                    options: $.extend(
                        anggaranDropdownOptions(),
                        {
                            panelMinWidth: 250,
                        },
                        options
                    ),
                };
            },
        },
        formatter: {
            ref: (refName, fieldName) => {
                return (value, row, index) => {
                    if (fieldName) {
                        return yii.app.getRef(refName, row[fieldName]);
                    }
                    return yii.app.getRef(refName, value);
                };
            }
        },
        filter: {
            ref: function (dgEl, fieldName, refName, options = {}) {
                return {
                    field: fieldName,
                    type: 'combobox',
                    options: itemRefDropdown(
                        refName,
                        $.extend({
                            panelHeight: 'auto',
                            onChange: function (value) {
                                if (value === '') {
                                    dgEl.datagrid('removeFilterRule', fieldName);
                                } else {
                                    dgEl.datagrid('addFilterRule', {
                                        field: fieldName,
                                        op: 'equal',
                                        value: value
                                    });
                                }
                                dgEl.datagrid('doFilter');
                            },
                            icons: [
                                {
                                    iconCls: 'icon-clear',
                                    handler: function (e) {
                                        $(e.data.target).combobox('clear');
                                    }
                                }
                            ],
                        }, options)
                    ),
                };
            },
            /**
             * 
             * @param {object} dgEl
             * @param {string} field
             * @param {string} op operator: 
             *        nofilter
             *        contains          : LIKE
             *        equal             : =
             *        notequal          : !=
             *        beginwith
             *        endwith           
             *        less              : <
             *        lessorequal       : <=
             *        greater           : >
             *        greaterorequal    : >=
             *        
             * @param {bool} insensitive
             * @param {string} type optional
             * @param {string || object} event optional
             * @param {object} isDate optional
             * @return {appapp.appAnonym$14.default.filter.appAnonym$25}
             */
            customize: function (dgEl, field, op, insensitive, type, event, isDate) {
                event = event || 'onChange';
                if (typeof event === 'object') {
                    var options = event;
                } else {
                    var options = {};
                    options[event] = function (value) {
                        if (value === '') {
                            dgEl.datagrid('removeFilterRule', field);
                        } else {
                            var rule = {
                                field: field,
                                op: op,
                                value: value
                            };

                            if (insensitive) {
                                rule.insensitive = true;
                            }

                            if (isDate) {
                                rule.is_date = true;
                            }

                            dgEl.datagrid('addFilterRule', rule);
                        }
                        dgEl.datagrid('doFilter');
                    };
                }
                return {
                    field: field,
                    type: type || 'textbox',
                    options: options
                };
            }
        },
        ajax: {
            delete: (options) => {
                $.messager.confirm('Hapus', 'Yakin akan menghapus ' + options.text + '?', function (r) {
                    if (r) {
                        ajaxDelete(options);
                    }
                });
            },
        },
        dialog: {
            pdf: (elementName, row, typePdf, force = false, canGenerate = true, canUpload = false) => {
                return initDocDlg(elementName, row, typePdf, force, canGenerate, canUpload);
            }
        }
    };
})(window.jQuery);

$.extend($.fn.pagination.defaults, {
    beforePageText: '',
    displayMsg: '{from}-{to}/{total}'
});

$.extend($.fn.datagrid.defaults, {
    loadFilter: (data) => {
        data.total = data._meta.totalCount;
        return data;
    },
});

$.extend($.fn.menubutton.defaults, {
    showEvent: 'click',
});

$.extend($.fn.datagrid.methods, {
    toExcel: function (jq, filename) {
        return jq.each(function () {
            var uri = 'data:application/vnd.ms-excel;base64,';
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
            var base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)));
            };
            var format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                });
            };
            var alink = $('<a style="display:none"></a>').appendTo('body');
            //var table = $(this).datagrid('getPanel').find('div.datagrid-view2 table.datagrid-btable');
            var table = $(this).datagrid('getPanel').find('div.datagrid-view2');
            var ctx = { worksheet: name || 'Worksheet', table: table.html() || '' };
            alink[0].href = uri + base64(format(template, ctx));
            alink[0].download = filename;
            alink[0].click();
            alink.remove();
        });
    }
});
//$.extend($.fn.datagrid.methods, {
//    toExcel: function (jq, filename) {
//        return jq.each(function () {
//            var uri = 'data:application/vnd.ms-excel;base64,';
//            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
//            var base64 = function (s) {
//                return window.btoa(unescape(encodeURIComponent(s)));
//            };
//            var format = function (s, c) {
//                return s.replace(/{(\w+)}/g, function (m, p) {
//                    return c[p];
//                });
//            };
//
//            var alink = $('<a style="display:none"></a>').appendTo('body');
//            var view = $(this).datagrid('getPanel').find('div.datagrid-view');
//            var table = view.find('div.datagrid-view2 table.datagrid-btable').clone();
//            var tbody = table.find('>tbody');
//            view.find('div.datagrid-view1 table.datagrid-btable>tbody>tr').each(function (index) {
//                $(this).clone().children().prependTo(tbody.children('tr:eq(' + index + ')'));
//            });
//            var ctx = {worksheet: name || 'Worksheet', table: table.html() || ''};
//            alink[0].href = uri + base64(format(template, ctx));
//            alink[0].download = filename;
//            alink[0].click();
//            alink.remove();
//        });
//    }
//});

$.extend($.fn.validatebox.defaults.rules, {
    maxFileSize: {
        validator: function (value, param) {
            var file = $(this).parent().find('.textbox-value');
            var sizeKB = ((file[0].files[0].size) / 1024).toFixed(0);
            return sizeKB <= param[0];
        },
        message: 'The file must be less than {0}KB.'
    }
});

$.fn.datebox.defaults.formatter = function (date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
};

$.fn.datebox.defaults.parser = function (s) {
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        return new Date(y, m - 1, d);
    } else {
        return new Date();
    }
};

//Formatter
// $.fn.datebox.defaults.formatter = function (date) {
//     var y = date.getFullYear();
//     var m = date.getMonth() + 1;
//     var d = date.getDate();
//     return (d < 10 ? ("0" + d) : d) + "-" + (m < 10 ? ("0" + m) : m) + "-" + y;
// };
// $.fn.datebox.defaults.parser = function (s) {
//     if (!s) {
//         return new Date();
//     }
//     var ss = s.split("-");
//     var m = parseInt(ss[1], 10);
//     var d = parseInt(ss[0], 10);
//     var y = parseInt(ss[2], 10);
//     if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
//         return new Date(y, m - 1, d);
//     } else {
//         return new Date();
//     }
// };


$.extend($.fn.validatebox.defaults.rules, {
    dateLessEqual: {
        validator: function (value, param) {
            var d = prepareEqualDate(value, param);
            return d.d2 <= d.d1;
        },
        message: 'Tanggal harus sebelum atau sama dengan {0}.'
    },
    dateMoreEqual: {
        validator: function (value, param) {
            var d = prepareEqualDate(value, param);
            return d.d2 >= d.d1;
        },
        message: 'Tanggal harus sesudah atau sama dengan {0}'
    },
    validDate: {
        validator: function (value) {
            var date = $.fn.datebox.defaults.parser(value);
            var s = $.fn.datebox.defaults.formatter(date);
            return s === value;
        },
        message: 'Isikan tanggal yang benar.'
    },
    justText: {
        validator: function (value, param) {
            return !value.match(/[0-9]/);
        },
        message: 'Isian hanya teks saja.'
    },
    justNumber: {
        validator: function (value, param) {
            return value.match(/^\d+$/);
        },
        message: 'Isian hanya angka saja.'
    },
    inList: {
        /**
         * 
         * @param {text} value 
         * @param {Array} param index 0 is element; index 2 is valueField  
         * @returns bool
         */
        validator: function (value, param) {
            let exist = false;
            let selectedValue = param[0].combobox('getValue');
            param[0].combobox('getData').forEach((v) => {
                if (selectedValue == v[param[1]]) {
                    exist = true;
                    return;
                }
            });
            return exist;
        },
        message: 'Harus pilih pilihan yang muncul.'
    },
    equalTo: {
        validator: function (value, param) {
            return value == $(param[0]).val();
        },
        message: 'Isian harus sama.'
    },
    diffTo: {
        validator: function (value, param) {
            return value !== $(param[0]).val();
        },
        message: 'Isian harus berbeda.'
    },
});