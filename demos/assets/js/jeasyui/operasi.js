window.yii.app.operasi = (function ($) {
    var el;
    var eastEL;
    var eastCenterEl;
    var dg;
    var giatDg;
    var dokumenDg;
    var pg;
    var dgRow;

    var operasiForm;
    var operasiDlg;
    var sandiInput;
    var satkerInput;
    var tanggalMulaiInput;
    var tanggalSelesaiInput;
    var dokumenForm;
    var dokumenDlg;
    var tentangInput;
    var fileDokumenInput;
    var keteranganInput;
    var giatForm;
    var giatDlg;
    var tanggalGiatInput;
    var fileRenGiatInput;
    var fileHasilGiatInput;

    var operasiFormDlg = function (row) {
        var title = 'Operasi Baru';
        var url;
        if (row) {
            title = 'Edit Operasi';
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({
                r: 'v1/operasi/update',
                id: row.id
            }, true);
        } else {
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({ r: 'v1/operasi/create' }, true);
        }

        if (operasiDlg) {
            operasiDlg.dialog('setTitle', title);
            operasiDlg.dialog('open');
            operasiForm.form({ url: url });
            if (row) {
                operasiForm.form('load', row);
                satkerInput.combobox('setValue', row.satker_id);
            } else {
                operasiForm.form('reset');
            }
        } else {
            operasiDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: 450,
                width: 355,
                buttons: [
                    {
                        iconCls: 'icon-disk',
                        text: 'Simpan',
                        handler: function () {
                            operasiForm.form('submit');
                        }
                    }
                ],
                content: '<div style="padding: 20px"><form id="operasi-form" method="post"></form></div>'
            });

            operasiForm = operasiDlg.find('#operasi-form');

            sandiInput = $('<input name="sandi" style="border: none"/>');
            operasiForm.append(sandiInput);
            sandiInput.textbox({
                label: 'Sandi: ',
                labelPosition: 'top',
                width: 300,
                required: true
            });

            satkerInput = $('<input name="satker_id" />');
            operasiForm.append(satkerInput);
            yii.app.refDropdown('satker', satkerInput, function () {
                //satkerInput.combobox('setValue', row.satker_id);
            }, {
                required: true
            });

            tanggalMulaiInput = $('<input name="tanggal_mulai"/>');
            operasiForm.append(tanggalMulaiInput);
            tanggalMulaiInput.datebox({
                label: 'Tanggal Mulai: ',
                labelPosition: 'top',
                width: 300,
                required: true,
            });

            tanggalSelesaiInput = $('<input name="tanggal_selesai"/>');
            operasiForm.append(tanggalSelesaiInput);
            tanggalSelesaiInput.datebox({
                label: 'Tanggal Selesai: ',
                labelPosition: 'top',
                width: 300,
                required: true,
            });


            operasiForm.form({
                url: url,
                success: function (data) {
                    operasiDlg.dialog('close');
                    dg.datagrid('reload');
                }
            });

            if (row) {
                operasiForm.form('load', row);
            }
        }
    };

    var giatFormDlg = function (row) {
        var title = 'Upload RenGiat Baru';
        var url;
        if (row) {
            title = 'Upload Laporan Hasil Giat';
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({
                r: 'v1/operasi-giat/update',
                id: row.id
            }, true);
        } else {
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({ r: 'v1/operasi-giat/create' }, true);
        }

        if (giatDlg) {
            giatDlg.dialog('setTitle', title);
            giatDlg.dialog('open');
            giatForm.form({ url: url });
            if (row) {
                giatForm.form('load', row);
                tanggalGiatInput.datebox({readonly: true});
                fileRenGiatInput.filebox({ required: false });
                fileHasilGiatInput.filebox({ required: true });
                fileRenGiatInput.filebox('hide');
                fileHasilGiatInput.filebox('show');
            } else {
                giatForm.form('reset');
                tanggalGiatInput.datebox({readonly: false});
                fileRenGiatInput.filebox({ required: true });
                fileHasilGiatInput.filebox({ required: false });
                fileRenGiatInput.filebox('show');
                fileHasilGiatInput.filebox('hide');
                $('#operasi-giat-operasi-id').val(dgRow.id);
            }
        } else {
            giatDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: 450,
                width: 355,
                buttons: [
                    {
                        iconCls: 'icon-disk',
                        text: 'Simpan',
                        handler: function () {
                            giatForm.form('submit');
                        }
                    }
                ],
                content: '<div style="padding: 20px"><form id="operasi-giat-form" method="post" enctype="multipart/form-data"><input type="hidden" value="' + dgRow.id + '" name="operasi_id" id="operasi-giat-operasi-id"></form></div>'
            });

            giatForm = giatDlg.find('#operasi-giat-form');

            tanggalGiatInput = $('<input name="tanggal"/>');
            giatForm.append(tanggalGiatInput);
            tanggalGiatInput.datebox({
                label: 'Tanggal: ',
                labelPosition: 'top',
                width: 300,
                required: true,
            });

            fileRenGiatInput = $('<input name="pdfRenGiatFile" style="border: none"/>');
            giatForm.append(fileRenGiatInput);
            fileRenGiatInput.filebox({
                label: 'File PDF RenGiat: ',
                labelPosition: 'top',
                accept: '.pdf',
                buttonText: 'Pdf File',
                buttonIcon: 'icon-pdf',
                required: row ? false : true,
                width: 300,
                icons: [{
                    iconCls: 'icon-clear',
                    handler: function (e) {
                        fileRenGiatInput.box('clear');
                    }
                }]
            });

            fileHasilGiatInput = $('<input name="pdfHasilGiatFile" style="border: none"/>');
            giatForm.append(fileHasilGiatInput);
            fileHasilGiatInput.filebox({
                label: 'File PDF Laporan Hasil Giat: ',
                labelPosition: 'top',
                accept: '.pdf',
                buttonText: 'Pdf File',
                buttonIcon: 'icon-pdf',
                required: row ? false : true,
                width: 300,
                icons: [{
                    iconCls: 'icon-clear',
                    handler: function (e) {
                        fileHasilGiatInput.box('clear');
                    }
                }]
            });

            giatForm.form({
                url: url,
                iframe: false,
                success: function (data) {
                    giatDlg.dialog('close');
                    giatDg.datagrid('reload');
                }
            });

            if (row) {
                tanggalGiatInput.datebox({readonly: true});
                fileRenGiatInput.filebox({ required: false });
                fileHasilGiatInput.filebox({ required: true });
                fileRenGiatInput.filebox('hide');
                fileHasilGiatInput.filebox('show');
                giatForm.form('load', row);
            } else {
                tanggalGiatInput.datebox({readonly: false});
                fileRenGiatInput.filebox({ required: true });
                fileHasilGiatInput.filebox({ required: false });
                fileRenGiatInput.filebox('show');
                fileHasilGiatInput.filebox('hide');
            }
        }
    };

    var dokumenFormDlg = function (row) {
        var title = 'Dokumen Operasi Baru';
        var url;
        if (row) {
            title = 'Edit Dokumen Operasi';
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({
                r: 'v1/operasi-dokumen/update',
                id: row.id
            }, true);
        } else {
            url = yii.easyui.getHost('api') + yii.easyui.ajaxAuthToken({ r: 'v1/operasi-dokumen/create' }, true);
        }

        if (dokumenDlg) {
            dokumenDlg.dialog('setTitle', title);
            dokumenDlg.dialog('open');
            dokumenForm.form({ url: url });
            if (row) {
                dokumenForm.form('load', row);
                fileDokumenInput.filebox({ required: false });
                fileDokumenInput.filebox('hide');
            } else {
                dokumenForm.form('reset');
                $('#operasi-dokumen-operasi-id').val(dgRow.id);
                fileDokumenInput.filebox({ required: true });
                fileDokumenInput.filebox('show');
                fileDokumenInput.box('clear');
            }
        } else {
            dokumenDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: 450,
                width: 355,
                buttons: [
                    {
                        iconCls: 'icon-disk',
                        text: 'Simpan',
                        handler: function () {
                            dokumenForm.form('submit');
                        }
                    }
                ],
                content: '<div style="padding: 20px"><form id="operasi-dokumen-form" method="post" enctype="multipart/form-data"><input type="hidden" value="' + dgRow.id + '" name="operasi_id" id="operasi-dokumen-operasi-id"></form></div>'
            });

            dokumenForm = dokumenDlg.find('#operasi-dokumen-form');

            tentangInput = $('<input name="tentang" style="border: none"/>');
            dokumenForm.append(tentangInput);
            tentangInput.textbox({
                label: 'Tentang: ',
                labelPosition: 'top',
                width: 300,
                required: true
            });

            fileDokumenInput = $('<input name="pdfFile" style="border: none"/>');
            dokumenForm.append(fileDokumenInput);
            fileDokumenInput.filebox({
                label: 'File PDF: ',
                labelPosition: 'top',
                accept: '.pdf',
                buttonText: 'Pdf File',
                buttonIcon: 'icon-pdf',
                required: row ? false : true,
                width: 300,
                icons: [{
                    iconCls: 'icon-clear',
                    handler: function (e) {
                        fileDokumenInput.box('clear');
                    }
                }]
            });

            keteranganInput = $('<input name="keterangan" style="border: none"/>');
            dokumenForm.append(keteranganInput);
            keteranganInput.textbox({
                label: 'Keterangan: ',
                labelPosition: 'top',
                width: 300,
                multiline: 1,
            });

            dokumenForm.form({
                url: url,
                iframe: false,
                success: (data) => {
                    yii.easyui.hideMainMask();
                    dokumenDlg.dialog('close');
                    dokumenDg.datagrid('reload');
                },
                error: (xhr) => {
                    return yii.easyui.ajaxError(xhr);
                },
                onSubmit: function (param) {
                    var opt = fileDokumenInput.filebox('options');
                    if (opt.required && !fileDokumenInput.filebox('files').length) {
                        $.messager.alert('Upload', 'Silahkan pilih file yang akan diupload.', 'error');
                        return false;
                    }
                    yii.easyui.showMainMask();
                }
            });

            if (row) {
                fileDokumenInput.filebox('hide');
                dokumenForm.form('load', row);
            }
        }
    };

    var giatPreviewDlg;
    var giatPreview = function (filename) {
        var title = 'PDF Preview';
        if (giatPreviewDlg) {
            giatPreviewDlg.dialog('open');
        } else {
            giatPreviewDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: '80%',
                width: '80%'
            });
        }
        giatPreviewDlg.dialog({
            // content: '<iframe src="' + yii.easyui.getHost('frontend') + '/pdf/operasi-giat&filename=' + filename + '" style="width:100%;height:100%;border:none;"></iframe>'
            
            //demo purpose
            content: '<iframe src="' + yii.easyui.getHost('api') + '/pdf/' + filename + '" style="width:100%;height:100%;border:none;"></iframe>'
        });
    };

    var dokumenPreviewDlg;
    var dokumenPreview = function (filename) {
        var title = 'PDF Preview';
        if (dokumenPreviewDlg) {
            dokumenPreviewDlg.dialog('open');
        } else {
            dokumenPreviewDlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: '80%',
                width: '80%'
            });
        }
        dokumenPreviewDlg.dialog({
            //content: '<iframe src="' + yii.easyui.getHost('frontend') + '/pdf/operasi-dokumen&filename=' + filename + '" style="width:100%;height:100%;border:none;"></iframe>'
            
            //demo purpose
            content: '<iframe src="' + yii.easyui.getHost('api') + '/pdf/' + filename + '" style="width:100%;height:100%;border:none;"></iframe>'
        });
    };

    var pgData = function (row) {
        row = row || {};
        return {
            rows: [
                {
                    name: 'Sandi',
                    value: row.sandi,
                    group: 'Sandi',
                },
                {
                    name: 'Satker',
                    value: yii.app.getRef('satker', row.satker_id, function () {
                        pg.propertygrid('loadData', pgData(row));
                    }),
                    group: 'Umum',
                },
                {
                    name: 'Tanggal Mulai',
                    value: row.tanggal_mulai,
                    group: 'Umum',
                },
                {
                    name: 'Tanggal Selesai',
                    value: row.tanggal_selesai,
                    group: 'Umum',
                },
            ]
        };
    };

    var initDg = () => {
        dg = el.find('#operasi-dg');
        dg.datagrid({
            //demos purpose
            url: yii.easyui.getHost('api') + '/operasi-ext-satker.json',

            // url: yii.easyui.getHost('api'),
            queryParams: yii.easyui.ajaxAuthToken({
                r: 'v1/jeasyui/operasi',
                expand: 'satker',
                fields: 'id, sandi, satker_id, tanggal_mulai, tanggal_selesai, satker.nama',
            }),
            method: 'get',
            fit: true,
            border: false,
            striped: true,
            checkOnSelect: false,
            singleSelect: true,
            emptyMsg: 'No Records Found.',
            rownumbers: true,
            pagination: true,
            pageSize: 50,
            remoteFilter: true,
            sortName: 'tanggal_mulai',
            sortOrder: 'desc',
            remoteSort: true,
            columns: [
                [
                    { field: 'ck', checkbox: true },
                    {
                        field: 'sandi', title: 'Sandi',
                        sortable: true,
                        formatter: (value, row, index) => {
                            return row.sandi;
                        }
                    },
                    {
                        field: 'satker_id', title: 'Satker / Wilayah',
                        formatter: (value, row, index) => {
                            return row.satker.nama;
                        }
                    },
                ]
            ],
            toolbar: [
                {
                    text: 'Baru',
                    iconCls: 'icon-add',
                    handler: function () {
                        operasiFormDlg();
                    }
                }, {
                    text: 'Edit',
                    iconCls: 'icon-edit',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Edit Operasi', 'Pilih data yang akan diedit.', 'error');
                        }
                        operasiFormDlg(dgRow);
                    }
                }, '-',
                {
                    text: 'Hapus',
                    iconCls: 'icon-remove',
                    handler: function () {
                        let row = dg.datagrid('getSelected');
                        if(!row){
                            return $.messager.alert('Edit Operasi', 'Pastikan ada data operasi yang dipilih.', 'error');
                        }
                        yii.app.ajax.delete({
                            id: row.id,
                            route: 'v1/operasi/delete',
                            text: 'Operasi',
                            callback: () => {
                                dg.datagrid('reload');
                            }
                        });
                    }
                }
            ],
            onSelect: function (index, row) {
                dgRow = row;
                pg.propertygrid('loadData', pgData(row));
                giatDg.datagrid({
                    //demo purpose
                    url: yii.easyui.getHost('api') + '/operasi-giat.json',

                    //url: yii.easyui.getHost('api'),
                    queryParams: yii.easyui.ajaxAuthToken({
                        r: 'v1/jeasyui/operasi-giat',
                        operasi_id: row.id
                    })
                });
                dokumenDg.datagrid('addFilterRule', { field: 'operasi_id', op: 'equal', value: row.id });
                dokumenDg.datagrid({
                    // demo purpose
                    url: yii.easyui.getHost('api') + '/operasi-dokumen.json',

                    //url: yii.easyui.getHost('api'),
                    queryParams: yii.easyui.ajaxAuthToken({
                        r: 'v1/jeasyui/operasi-dokumen',
                    })
                });
            },
            onLoadSuccess: function (data) {
                $(this).datagrid('selectRow', 0);
            },
            onLoadError: function (xhr) {
                yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        dg.datagrid('reload');
                    }
                });
            }
        }).datagrid('enableFilter', [
            yii.app.filter.customize(dg, 'sandi', 'contains', true),
            yii.app.filter.customize(dg, 'satker_id', 'equal', true),
        ]);
    };

    var initGiatDg = () => {
        giatDg = el.find('#operasi-giat-dg');
        giatDg.datagrid({
            method: 'get',
            fit: true,
            border: false,
            striped: true,
            checkOnSelect: false,
            singleSelect: true,
            emptyMsg: 'No Records Found.',
            rownumbers: true,
            pagination: true,
            pageSize: 50,
            remoteFilter: true,
            sortName: 'tanggal',
            remoteSort: true,
            columns: [
                [
                    { field: 'ck', checkbox: true },
                    {
                        field: 'tanggal', title: 'Tanggal',
                        sortable: true,
                    },
                    {
                        field: 'pdf_filename_rencana', title: 'Rencana',
                        formatter: (value, row, index) => {
                            return '<a href="javascript:void(0);" onclick="yii.app.operasi.giatPreview(\'' + row.pdf_filename_rencana + '\')">' + row.pdf_filename_rencana + '</a>';
                        }
                    },
                    {
                        field: 'pdf_filename_hasil', title: 'Hasil',
                        formatter: (value, row, index) => {
                            if (row.pdf_filename_hasil !== null) {
                                return '<a href="javascript:void(0);" onclick="yii.app.operasi.giatPreview(\'' + row.pdf_filename_hasil + '\')">' + row.pdf_filename_hasil + '</a>';
                            }
                            return '-';
                        }
                    },
                ]
            ],
            toolbar: [
                {
                    text: 'RenGiat Baru',
                    iconCls: 'icon-add',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Upload Ren Giat', 'Pastikan ada data operasi yang dipilih.', 'warning');
                        }
                        giatFormDlg();
                    }
                }, {
                    text: 'Upload Hasil',
                    iconCls: 'icon-pdf',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Upload Hasil Giat', 'Pastikan ada data operasi yang dipilih.', 'warning');
                        }
                        giatFormDlg(giatDg.datagrid('getSelected'));
                    }
                }, '-',
                {
                    text: 'Hapus',
                    iconCls: 'icon-remove',
                    handler: function () {
                        let row = giatDg.datagrid('getSelected');
                        if(!row){
                            return $.messager.alert('Hapus Ren dan Hasil Giat', 'Tidak ada data yang dipilih.', 'warning');
                        }
                        yii.app.ajax.delete({
                            id: row.id,
                            route: 'v1/operasi-giat/delete',
                            text: 'Giat Operasi',
                            callback: () => {
                                giatDg.datagrid('reload');
                            }
                        });
                    }
                }
            ],
            onLoadSuccess: function (data) {
                $(this).datagrid('selectRow', 0);
            },
            onLoadError: function (xhr) {
                yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        giatDg.datagrid('reload');
                    }
                });
            }
        }).datagrid('enableFilter', [
            yii.app.filter.customize(giatDg, 'tanggal', 'contains', true),
            yii.app.filter.customize(giatDg, 'pdf_filename_rencana', 'contains', true),
            yii.app.filter.customize(giatDg, 'pdf_filename_hasil', 'contains', true),
        ]);
    };

    var initDocDg = (row) => {
        dokumenDg = el.find('#operasi-doc-dg');
        dokumenDg.datagrid({
            method: 'get',
            fit: true,
            border: false,
            striped: true,
            checkOnSelect: false,
            singleSelect: true,
            emptyMsg: 'No Records Found.',
            rownumbers: true,
            pagination: true,
            pageSize: 50,
            remoteFilter: true,
            sortName: 'tanggal',
            remoteSort: true,
            columns: [
                [
                    { field: 'ck', checkbox: true },
                    {
                        field: 'tentang', title: 'Tentang',
                        sortable: true,
                    },
                    {
                        field: 'pdf_filename', title: 'File',
                        sortable: true,
                        formatter: (value, row, index) => {
                            return '<a href="javascript:void(0);" onclick="yii.app.operasi.dokumenPreview(\'' + row.pdf_filename + '\')">' + row.pdf_filename + '</a>';
                        }
                    },
                    {
                        field: 'keterangan', title: 'Keterangan',
                        sortable: true,
                    },
                    {
                        field: 'created_at', title: 'Diupload pada',
                        sortable: true,
                        formatter: (value, row, index) => {
                            return new Date(row.created_at * 1000).toLocaleString();
                        }
                    },
                ]
            ],
            toolbar: [
                {
                    text: 'Baru',
                    iconCls: 'icon-add',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Upload Dokumen', 'Pastikan ada data operasi yang dipilih.', 'warning');
                        }
                        dokumenFormDlg();
                    }
                }, {
                    text: 'Edit',
                    iconCls: 'icon-edit',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Edit dokumen', 'Pastikan ada data operasi yang dipilih.', 'warning');
                        }
                        dokumenFormDlg(dokumenDg.datagrid('getSelected'));
                    }
                }, '-',
                {
                    text: 'Hapus',
                    iconCls: 'icon-remove',
                    handler: function () {
                        let row = dokumenDg.datagrid('getSelected');
                        if(!row){
                            return $.messager.alert('Hapus dokumen', 'Tidak ada data dokumen yang dipilih.', 'warning');
                        }
                        yii.app.ajax.delete({
                            id: row.id,
                            route: 'v1/operasi-dokumen/delete',
                            text: 'Dokumen Operasi',
                            callback: () => {
                                dokumenDg.datagrid('reload');
                            }
                        });
                    }
                }
            ],
            onLoadSuccess: function (data) {
                $(this).datagrid('selectRow', 0);
            },
            onLoadError: function (xhr) {
                yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        dokumenDg.datagrid('reload');
                    }
                });
            }
        }).datagrid('enableFilter', [
            yii.app.filter.customize(dokumenDg, 'tanggal', 'contains', true),
            yii.app.filter.customize(dokumenDg, 'pdf_filename_rencana', 'contains', true),
            yii.app.filter.customize(dokumenDg, 'pdf_filename_hasil', 'contains', true),
        ]);
    };

    var initPg = () => {
        pg = eastEL.find('#operasi-pg');
        pg.propertygrid({
            showGroup: true,
            scrollbarSize: 0,
            fit: true,
            border: false,
            nowrap: false,
            data: pgData()
        });
    };

    return {
        isActive: false,
        init: function () {
            el = $('#operasi-index');
            el.layout({
                fit: true,
                border: false
            }).layout('add', {
                region: 'east',
                split: true,
                border: false,
                content: '<div id="operasi-east"></div>',
                width: '67%',
            }).layout('add', {
                region: 'center',
                border: true,
                content: '<table id="operasi-dg"></table>'
            });

            eastEL = el.find('#operasi-east');
            eastEL.layout({
                fit: true,
                border: false
            }).layout('add', {
                region: 'center',
                border: false,
                content: '<div id="operasi-east-center"></div>'
            }).layout('add', {
                title: 'Giat',
                iconCls: 'icon-pdf',
                region: 'east',
                split: true,
                border: true,
                hideCollapsedContent: !1,
                content: '<table id="operasi-giat-dg"></table>',
                width: '50%'
            });

            eastCenterEl = el.find('#operasi-east-center');
            eastCenterEl.layout({
                fit: true,
                border: false
            }).layout('add', {
                title: 'Dokumen',
                iconCls: 'icon-pdf',
                region: 'center',
                split: false,
                content: '<table id="operasi-doc-dg"></table>',
                border: true,
            }).layout('add', {
                title: 'Rincian',
                region: 'north',
                border: true,
                hideCollapsedContent: !1,
                content: '<table id="operasi-pg"></table>',
                height: '50%'
            });


            initDg();

            initPg();

            initGiatDg();
            initDocDg();
        },
        dokumenPreview: (data) => {
            return dokumenPreview(data);
        },
        giatPreview: (data) => {
            return giatPreview(data);
        },
    };
})(window.jQuery);