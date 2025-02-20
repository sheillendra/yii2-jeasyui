<?php


/** @var yii\web\View $this */
/** @var sheillendra\jeasyui\gii\generators\crud\Generator $generator */

use yii\helpers\Inflector;

$id = Inflector::camel2id($baseModelClassName);
$variablize = Inflector::variablize($baseModelClassName);
$words = Inflector::camel2words($baseModelClassName);

$haveStatusActive = false;
$columnText = "{ field: 'ck', checkbox: true },\n";
$filterText = "\n";
$fieldText = "\n";
$varText = "\n";
$ajaxMapText = "\n";
$formText = '<div style="padding: 20px"><form id="' . $id . '-form" method="post"></form></div>';
$previewPdfText = "\n";
$previewPdfInit = "\n";

$schema = ($generator->modelClass)::getTableSchema();
$relations = [];
foreach($schema->foreignKeys as $k=>$v){
    $table = '';
    foreach($v as $kk=>$vv){
        if($kk == 0 ){
            $table = $vv;
        } else {
            $relations[$kk] = ['table' => $table, 'column' => $vv];
        }
    }    
}

$_comments = [];
$collectComment = function ($column) use(&$_comments) {
    $_comments[$column->name] = [];
    if ($column->comment) {
        $split1 = explode(';', $column->comment);
        foreach ($split1 as $k => $v) {
            $split2 = explode(':', $v);
            $_comments[$column->name][$split2[0]] = $split2[1];
        }
    }
};

$getLabel = function ($column) use (&$_comments){
    if (isset($_comments[$column->name]['label'])) {
        return $_comments[$column->name]['label'];
    }
    return str_replace(' Id', '', Inflector::camel2words($column->name));
};

$fungsi = function ($column, $comment) use (&$id, &$haveStatusActive, &$columnText, &$fieldText, &$filterText, &$varText, &$ajaxMapText, &$formText, &$previewPdfText, &$previewPdfInit, $relations, &$_comments, $getLabel) {
    $columnWords = $getLabel($column);
    $columnId = Inflector::camel2id($column->name);
    $columnVariablize = Inflector::variablize($column->name);
    $required = $column->allowNull==1 ? 'false':'true';
    $varText .= "    {$comment}var {$columnVariablize}Input;\n";
    $numberTypes = ['integer', 'smallint', 'double'];
    $widthGridColumn = isset($_comments[$column->name]['width'])? ', width: ' + $_comments[$column->name]['width'] : '';
    $validType = isset($_comments[$column->name]['validType'])??'';
        
    if(in_array($column->type, $numberTypes)){
        if($column->name === 'status_active'){
            $haveStatusActive = true;
            $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, formatter:(value, row, index)=>{return yii.easyui.ref.statusActive[value];} },\n";
            $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', 'statusActive', {editable: false}),\n";
        } else {
            if(isset($relations[$column->name])){
                $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, formatter:(value, row, index)=>{return yii.easyui.ref.{$columnVariablize} === undefined ? yii.easyui.ajax.map['{$columnVariablize}'](()=>{dg.datagrid('reload');dg.datagrid('getFilterComponent', '{$column->name}').combobox({'textField': 'name'}).combobox('loadData', yii.easyui.refResult['{$columnVariablize}']);}) : yii.easyui.ref.{$columnVariablize}[value];} },\n";
                $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', '{$columnVariablize}', {editable: false}),\n";
            }else{
                if(strpos($column->comment, 'format:currency') !== false){
                    $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, align: 'right', formatter: yii.easyui.filter.currency },\n";
                }else{
                    $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, align: 'right' },\n";
                }
                $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'equal', false, 'numberbox'),\n";
            }
        }
        
    }elseif($column->type == 'boolean'){
        $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', 'boolean', {editable: false}),\n";
        $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, formatter:(value, row, index)=>{return yii.easyui.ref.boolean[~~value];} },\n";
    }elseif(strpos($column->comment, 'accept:pdf') !== false){
        $previewPdfText .="    var pdfPreviewDlg;\n";
        $previewPdfText .="    var pdfPreview = function (src) {\n";
        $previewPdfText .="        var title = 'PDF Preview';\n";
        $previewPdfText .="        if (pdfPreviewDlg) {\n";
        $previewPdfText .="            pdfPreviewDlg.dialog('open');\n";
        $previewPdfText .="        } else {\n";
        $previewPdfText .="            pdfPreviewDlg = $('<div></div>').dialog({\n";
        $previewPdfText .="                title: title,\n";
        $previewPdfText .="                modal: true,\n";
        $previewPdfText .="                height: '80%',\n";
        $previewPdfText .="                width: '80%'\n";
        $previewPdfText .="            });\n";
        $previewPdfText .="        }\n";
        $previewPdfText .="        pdfPreviewDlg.dialog({\n";
        $previewPdfText .="            content: '<iframe src=\"' + src + '\" style=\"width:100%;height:100%;border:none;\"></iframe>'\n";
        $previewPdfText .="        });\n";
        $previewPdfText .="    };\n";

        $previewPdfInit .= "        pdfPreview: (url) => {\n";
        $previewPdfInit .= "            return pdfPreview(url);\n";
        $previewPdfInit .= "        },\n";
        $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn}, formatter: (value, row, index) => {if(value){return '<a href=\"javascript:void(0);\" onclick=\"yii.easyui.wifiVoucher.pdfPreview(\'' + yii.easyui.getHost('base') + '?r=pdf&name=' +encodeURIComponent(row.filename) + '\')\">' + row.filename + '</a>';}} },\n";
        $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'contains', true),\n";
    }else{
        $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$columnWords}'{$widthGridColumn} },\n";
        $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'contains', true),\n";
    }

    if($column->isPrimaryKey == 1 || (isset($_comments[$column->name]['formHide']) && $_comments[$column->name]['formHide'] == 'true')){
        return;
    }

    //element input
    if(strpos($column->comment, 'input:file') !== false){
        $inputFile = isset($_comments[$column->name]['inputName'])?$_comments[$column->name]['inputName']:'inputFile';
        if(strpos($column->comment, 'accept:image') !== false){
            $fieldText .="            {$comment}var {$columnVariablize}Input = $('<input name=\"{$inputFile}\" style=\"border: none\"/>');\n";
        }elseif(strpos($column->comment, 'accept:pdf') !== false){
            $fieldText .="            {$comment}var {$columnVariablize}Input = $('<input name=\"{$inputFile}\" style=\"border: none\"/>');\n";
        }
    }else{
        $fieldText .="            {$comment}var {$columnVariablize}Input = $('<input name=\"{$column->name}\" style=\"border: none\"/>');\n";
    }

    //easyui field
    $fieldText .="            {$comment}frm.append({$columnVariablize}Input);\n";
    if(in_array($column->type, $numberTypes)){
        if($column->name === 'status_active'){
            $fieldText .="            {$comment}yii.easyui.refDropdown('statusActive', {$columnVariablize}Input, ()=>{}, {editable: false, value: 1, required: {$required}});\n";
        } else {
            if(isset($relations[$column->name])){
                $relationTableId = Inflector::camel2id($relations[$column->name]['table']);
                //$columnWords = str_replace(' Id', '', $columnWords);
                $fieldText .="            {$comment}yii.easyui.refDropdown('{$columnVariablize}', {$columnVariablize}Input, ()=>{}, {editable: false, required: {$required}});\n";
                $ajaxMapText .="            {$comment}yii.easyui.ajax.map['{$columnVariablize}'] = (callback) => {\n";
                $ajaxMapText .="            {$comment}    return yii.easyui.ajax.ref(callback, '{$columnVariablize}', { r: 'api/v1/{$relationTableId}', fields: '{$relations[$column->name]['column']}, name', 'per-page': 50 }, 'id', 'name');\n";
                $ajaxMapText .="            {$comment}};\n";
                $ajaxMapText .="            {$comment}yii.easyui.ajax.queueCallback['{$columnVariablize}'] = yii.easyui.ajax.queueCallback['{$columnVariablize}']??[];\n";
                $ajaxMapText .="            {$comment}yii.easyui.refLabel['{$columnVariablize}'] = '{$columnWords}';\n";
            } else {
                $fieldText .="            {$comment}{$columnVariablize}Input.numberbox({\n";
                $fieldText .="            {$comment}    label: '{$columnWords}',\n";
                $fieldText .="            {$comment}    labelPosition: 'top',\n";
                $fieldText .="            {$comment}    width: 300,\n";
                $fieldText .="            {$comment}    required: {$required},\n";
                if(strpos($column->comment, 'format:currency') !== false){
                    $fieldText .="            {$comment}    formatter: yii.easyui.formatter.currency,\n";
                    $fieldText .="            {$comment}    parser: yii.easyui.parser.currency,\n";
                }
                $fieldText .="            {$comment}});\n";
            }
        }
    }elseif($column->type == 'boolean'){
        $fieldText .="            {$comment}yii.easyui.booleanDropdown({$columnVariablize}Input, {label: '{$columnWords}', required: {$required}});\n";
    }elseif(strpos($column->comment, 'input:file') !== false){
        $formText = '<div style="padding: 20px"><form id="' . $id . '-form" method="post" enctype="multipart/form-data"></form></div>';
        $fieldText .="            {$comment}{$columnVariablize}Input.filebox({\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    icons: [{\n";
        $fieldText .="            {$comment}        iconCls: 'icon-clear',\n";
        $fieldText .="            {$comment}        handler: function (e) {\n";
        $fieldText .="            {$comment}            {$columnVariablize}Input.filebox('clear');\n";
        $fieldText .="            {$comment}        },\n";
        $fieldText .="            {$comment}    }],\n";
        if(strpos($column->comment, 'accept:image') !== false){
            $fieldText .="            {$comment}    label: '{$columnWords}',\n";
            $fieldText .="            {$comment}    accept: 'image/*',\n";
            $fieldText .="            {$comment}    buttonText: 'Image File',\n";
        }elseif(strpos($column->comment, 'accept:pdf') !== false){
            $fieldText .="            {$comment}    label: '{$columnWords}',\n";
            $fieldText .="            {$comment}    accept: 'pdf/*',\n";
            $fieldText .="            {$comment}    buttonText: 'PDF File',\n";
        }else{
            $fieldText .="            {$comment}    label: '{$columnWords}',\n";
        }
        $fieldText .="            {$comment}    required: {$required}\n";
        $fieldText .="            {$comment}});\n";
    }elseif($column->type == 'date'){
        $fieldText .="            {$comment}{$columnVariablize}Input.date({\n";
        $fieldText .="            {$comment}    label: '{$columnWords}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    required: {$required},\n";
        $fieldText .="            {$comment}    validType: [{$validType}],\n";
        $fieldText .="            {$comment}});\n";
    }elseif($column->type == 'datetime'){
        $fieldText .="            {$comment}{$columnVariablize}Input.datetimebox({\n";
        $fieldText .="            {$comment}    label: '{$columnWords}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    required: {$required},\n";
        $fieldText .="            {$comment}});\n";
    }else{
        $fieldText .="            {$comment}{$columnVariablize}Input.textbox({\n";
        $fieldText .="            {$comment}    label: '{$columnWords}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    required: {$required},\n";
        $fieldText .="            {$comment}});\n";
    }
    $fieldText .="\n";
};

$class = $generator->modelClass;
$pk = $class::primaryKey();

$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        $columnWords = Inflector::camel2words($name);
        if (++$count < 6) {
            //echo "            '" . $name . "',\n";
            $columnText .= "                    { field: '{$name}', title: '{$columnWords}', width: 100 },\n";
        } else {
            //echo "            //'" . $name . "',\n";
            $columnText .= "                    //{ field: '{$name}', title: '{$columnWords}' },\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $collectComment($column);
        if(in_array($column->name, ['created_at', 'created_by', 'updated_at', 'updated_by'])) continue;
        //if (++$count < 6) {
            $fungsi($column, '');
        //} else {
        //    $fungsi($column, '//');
        //}
    }
}
?>

window.yii.easyui.<?= $variablize?> = (function ($) {
    var el;
    var dg;
    var dgRow;
    var dlg;
    var frm;<?=$varText?>

    var frmDlg = function(row){
        var title = 'New <?=$words?>';
        var url;
        if (row) {
            title = 'Edit <?=$words?>';
            url = yii.easyui.ajaxAuthToken({
                r: 'api/v1/<?=$id?>/update',
                id: row.<?=$pk[0]."\n"?>
            }, true);
        } else {
            url = yii.easyui.ajaxAuthToken({ r: 'api/v1/<?=$id?>/create' }, true);
        }

        if (dlg) {
            dlg.dialog('setTitle', title);
            dlg.dialog('open');
            frm.form({ url: url });
            if (row) {
                frm.form('load', row);
            } else {
                frm.form('reset');
                <?php
                if($haveStatusActive){
                    echo "if (statusActiveInput.combobox !== undefined) {\n";
                    echo "                    statusActiveInput.combobox('setValue', 1);\n";
                    echo "                }\n";
                }
                ?>
            }
        } else {
            dlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: 450,
                width: 355,
                buttons: [
                    {
                        iconCls: 'icon-disk',
                        text: 'Save',
                        handler: function () {
                            frm.form('submit');
                        }
                    }
                ],
                content: '<?=$formText?>'
            });

            frm = dlg.find('#<?=$id?>-form');
            <?=$fieldText?>

            frm.form({
                url: url,
                success: function (data) {
                    dlg.dialog('close');
                    dg.datagrid('reload');
                }
            });

            if (row) {
                frm.form('load', row);
            }
        }
    }

    var initDg = function(){
        dg = el.find('#<?=$id?>-dg');
        dg.datagrid({
            url: yii.easyui.getHost('base'),
            queryParams: yii.easyui.ajaxAuthToken({ r: 'api/v1/jeasyui/<?=$id?>' }),
            method: 'get',
            fit: true,
            fitColumns: true,
            border: false,
            striped: true,
            checkOnSelect: false,
            singleSelect: true,
            emptyMsg: 'No Records Found.',
            rownumbers: true,
            pagination: true,
            pageSize: 50,
            remoteFilter: true,
            columns: [
                [
                    <?= $columnText?>
                ]
            ],
            toolbar: [
                {
                    text: 'New',
                    iconCls: 'icon-add',
                    handler: function () {
                        frmDlg();
                    }
                }, {
                    text: 'Edit',
                    iconCls: 'icon-edit',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Edit <?=$words?>', 'Select data to edit.', 'error');
                        }
                        frmDlg(dgRow);
                    }
                }, '-',
                {
                    text: 'Delete',
                    iconCls: 'icon-remove',
                    handler: function () {
                        if(!dgRow){
                            return $.messager.alert('Edit <?=$words?>', 'Select data to delete.', 'error');
                        }
                        yii.easyui.ajax.request({
                            id: dgRow.id,
                            route: 'api/v1/<?=$id?>/delete',
                            text: '<?=$words?>',
                            type: 'DELETE',
                            callback: () => {
                                dg.datagrid('reload');
                            }
                        });
                    }
                }
            ],
            onSelect: function (index, row) {
                dgRow = row;
            },
            onLoadSuccess: function (data) {
                $(this).datagrid('selectRow', 0);
            },
            loadFilter: function(data){
                data.total = data._meta.totalCount;
                return data;
            },
            onLoadError: function (xhr) {
                yii.easyui.ajaxError(xhr, function (r) {
                    if (r) {
                        dg.datagrid('reload');
                    }
                });
            }
        }).datagrid('enableFilter', [<?=$filterText?>
        ]).datagrid('columnPriority');
    }
<?=$previewPdfText?>

    return {
        init: function(){
            el = $('#<?=$id?>-index');
            el.layout({
                fit: true,
                border: false
            }).layout('add', {
                title: 'Detail',
                region: 'east',
                split: true,
                border: true,
                content: '<div id="<?=$id?>-east"></div>',
                width: '40%'
            }).layout('add', {
                region: 'center',
                border: true,
                content: '<table id="<?=$id?>-dg"></table>'
            });

            <?=$ajaxMapText?>

            initDg();
        },<?=$previewPdfInit?>
    }
})(jQuery);