<?php

/**
 * example use:
 *   public function getEasyuiAttributes()
 *   {
 *       return [
 *           '_' => [
 *               'iconCls' => 'fa-solid fa-ticket',
 *               'formDialogHeight' => 550,
 *               'sortName' => 'date,id',
 *               'sortOrder' => 'desc,asc',
 *               'remoteSort' => true,
 *               'multiSort' => true,
 *               'haveDetail' => false,
 *           ],
 *           'id' => [
 *               'width' => 60,
 *               'hideFromGrid' => true,
 *           ],
 *           'fix_price' => [
 *               'hideFromForm' => true,
 *               'hideFromGrid' => true,
 *               'format' => 'currency'
 *           ],
 *           'pdf_name' => [
 *               'hideFromForm' => true,
 *               'hideFromGrid' => true,
 *           ],
 *           'pdf_file' => [
 *               'input' => 'file',
 *               'accept' => 'pdf',
 *               'inputName' => 'inputFile',
 *               'fileName' => 'pdf_name',
 *           ],
 *           'date' => [
 *               'validType' => "'validDate','dateLessEqual[]'",
 *               'sortable' => true
 *           ]
 *       ];
 *   }
 */

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
$sortGrid = "\n";
$resetSort = "\n";
$haveSortable = false;
$publicVar = "";

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

$model = new ($generator->modelClass);
$labels = $model->attributeLabels();
if(method_exists($model, 'getEasyuiAttributes')){
    $easyuiAttributes = $model->easyuiAttributes;
} else {
    $easyuiAttributes = [];
}

//dipakai di dalam untuk mengakses model relation
$easyuiAttributes['modelNamespace'] = str_replace($baseModelClassName, '', $generator->modelClass);

$fungsi = function ($column, $comment) use (
    &$id,
    &$haveStatusActive, 
    &$columnText, 
    &$fieldText, 
    &$filterText, 
    &$varText, 
    &$ajaxMapText, 
    &$formText, 
    &$previewPdfText, 
    &$previewPdfInit, 
    &$haveSortable,
    &$publicVar,
    $relations, 
    $labels, 
    $easyuiAttributes,
    $variablize
    ) {

    $label = $labels[$column->name];
    $columnId = Inflector::camel2id($column->name);
    $columnVariablize = Inflector::variablize($column->name);
    $required = $column->allowNull==1 ? 'false':'true';
    $numberTypes = ['integer', 'smallint', 'double', 'float'];

    $validType = '';
    $widthGridColumn = '';
    $isCurrenctyFormat = false;
    $isFileInput = false;
    $acceptFileInput = '';
    $hideFromForm = false;
    $hideFromGrid = false;
    $inputName = $column->name;
    $boolean = false;
    $sortable = '';
    $panelHeight = '';

    if(in_array($column->type, ['string', 'text'])){
        $defaultValue = $column->defaultValue !==null ? ", value: '$column->defaultValue'" : '';
        $defaultValueNewLine = $column->defaultValue !==null? "            {$comment}    value: '$column->defaultValue',\n" : '';
    }elseif(in_array($column->type, ['boolean'])){
        if($column->defaultValue == true){
            $defaultValue = ", value: 1";
            $defaultValueNewLine = "            {$comment}    value: true,\n";
        }elseif($column->defaultValue == false){
            $defaultValue = ", value: 0";
            $defaultValueNewLine = "            {$comment}    value: false,\n";
        }else{
            $defaultValue = '';
            $defaultValueNewLine = '';    
        }
    }else{
        $defaultValue = $column->defaultValue !==null? ', value: ' . $column->defaultValue : '';
        $defaultValueNewLine = $column->defaultValue !==null? "            {$comment}    value: $column->defaultValue,\n" : '';
    }
    
    if(isset($easyuiAttributes[$column->name])){
        $easyuiAttribute = $easyuiAttributes[$column->name];
        $hideFromForm = isset($easyuiAttribute['hideFromForm']) ? $easyuiAttribute['hideFromForm'] : $hideFromForm;
        $hideFromGrid = isset($easyuiAttribute['hideFromGrid']) ? $easyuiAttribute['hideFromGrid'] : $hideFromGrid;
        $widthGridColumn = isset($easyuiAttribute['width'])? ', width: ' . $easyuiAttribute['width'] : $widthGridColumn;
        $validType = isset($easyuiAttribute['validType'])?$easyuiAttribute['validType'] : $validType;
        $inputName = isset($easyuiAttribute['inputName'])?$easyuiAttribute['inputName'] : $inputName;
        $isCurrenctyFormat = isset($easyuiAttribute['format']) && $easyuiAttribute['format'] === 'currency' ? true : false;
        $isFileInput = isset($easyuiAttribute['input']) && $easyuiAttribute['input'] === 'file' ? true : false;
        $acceptFileInput = isset($easyuiAttribute['accept']) ? $easyuiAttribute['accept'] : $acceptFileInput;
        $boolean = isset($easyuiAttribute['boolean']) ? $easyuiAttribute['boolean'] : $boolean;
        $panelHeight = isset($easyuiAttribute['panelHeight']) ? ', panelHeight: ' . $easyuiAttribute['panelHeight'] : '';
        
        if(isset($easyuiAttribute['sortable']) &&  $easyuiAttribute['sortable'] == true){
            $sortable =  ', sortable: true';
            $haveSortable = true;
        }
        
    }

    if($acceptFileInput === 'pdf'){
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
    }

    //grid
    if(!$hideFromGrid){
        $fileName = "row.{$column->name}";
        if(isset($easyuiAttribute['fileName'])){
            $fileName = "(row.{$easyuiAttribute['fileName']} || row.{$column->name})";
        }

        if(in_array($column->type, $numberTypes)){
            if($column->name === 'status_active'){
                $haveStatusActive = true;
                $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, formatter: (value, row, index) => { return yii.easyui.ref.statusActive[value]; } },\n";
                $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', 'statusActive', { editable: false }),\n";
            } elseif($boolean){
                $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', 'boolean', { editable: false }),\n";
                $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, formatter: (value, row, index) => { return yii.easyui.ref.boolean[~~value]; } },\n";
            } else {
                if(isset($relations[$column->name])){
                    $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, formatter: (value, row, index) => { return yii.easyui.ref.{$columnVariablize} === undefined ? yii.easyui.ajax.map['{$columnVariablize}'](() => { dg.datagrid('reload'); dg.datagrid('getFilterComponent', '{$column->name}').combobox({ 'textField': 'name' }).combobox('loadData', yii.easyui.refResult['{$columnVariablize}']); }) : yii.easyui.ref.{$columnVariablize}[value]; } },\n";
                    $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', '{$columnVariablize}', { editable: false }),\n";
                }else{
                    if($isCurrenctyFormat){
                        $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, align: 'right', formatter: yii.easyui.filter.currency },\n";
                    }else{
                        $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, align: 'right' },\n";
                    }
                    $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'equal', false, 'numberbox'),\n";
                }
            }
            
        }elseif($column->type == 'boolean'){
            $filterText .= "            {$comment}yii.easyui.filter.ref(dg, '{$column->name}', 'boolean', { editable: false }),\n";
            $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, formatter: (value, row, index) => { return yii.easyui.ref.boolean[~~value]; } },\n";
        }elseif($acceptFileInput === 'pdf'){
            $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable}, formatter: (value, row, index) => { if(value){return '<a href=\"javascript:void(0);\" onclick=\"yii.easyui.wifiVoucher.pdfPreview(\'' + yii.easyui.getHost('base') + '?r=pdf&name=' +encodeURIComponent(row.{$column->name}) + '\')\">' + {$fileName} + '</a>';}} },\n";
            $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'contains', true),\n";
        }elseif($column->type == 'date'){
            $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable} },\n";
            $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'equal', false, 'datebox'),\n";
        }else{
            $columnText .= "                    {$comment}{ field: '{$column->name}', title: '{$label}'{$widthGridColumn}{$sortable} },\n";
            $filterText .= "            {$comment}yii.easyui.filter.customize(dg, '{$column->name}', 'contains', true),\n";
        }
    }

    if($column->isPrimaryKey == 1){
        return;
    }

    if($hideFromForm){
        $varText .= "    {$comment}var {$columnVariablize}Input;\n";
        $fieldText .="            {$comment}{$columnVariablize}Input = $('<input name=\"{$inputName}\" type=\"hidden\" style=\"border: none\"/>');\n";
        $fieldText .="            {$comment}frm.append({$columnVariablize}Input);\n";

        if(isset($easyuiAttribute['showNameInForm']) &&  $easyuiAttribute['showNameInForm'] == true){
            $fieldText .= "            {$comment}var categoryName = $('<input name=\"category_name\" type=\"text\" style=\"border: none\"/>');\n"; 
            $fieldText .= "            {$comment}frm.append(categoryName);\n";
            $fieldText .= "            {$comment}categoryName.textbox({\n";
            $fieldText .= "            {$comment}    label: 'Category',\n";
            $fieldText .= "            {$comment}    labelPosition: 'top',\n"; 
            $fieldText .= "            {$comment}    width: 300,\n";
            $fieldText .= "            {$comment}    readonly: true,\n"; 
            $fieldText .= "            {$comment}});\n";
        }
        return;
    }

    if(isset($relations[$column->name])){
        $publicVar .= "\n        {$columnVariablize}Input: null,";
        $fieldText .="            {$comment}yii.easyui.{$variablize}.{$columnVariablize}Input = $('<input name=\"{$inputName}\" style=\"border: none\"/>');\n";
        $fieldText .="            {$comment}frm.append(yii.easyui.{$variablize}.{$columnVariablize}Input);\n";
    } else {
        $varText .= "    {$comment}var {$columnVariablize}Input;\n";
    
        //element input
        $fieldText .="            {$comment}{$columnVariablize}Input = $('<input name=\"{$inputName}\" style=\"border: none\"/>');\n";

        //easyui field
        $fieldText .="            {$comment}frm.append({$columnVariablize}Input);\n";
    }

    if(in_array($column->type, $numberTypes)){
        if($column->name === 'status_active'){
            $fieldText .="            {$comment}yii.easyui.refDropdown('statusActive', {$columnVariablize}Input, () => { }, { editable: false$defaultValue, required: {$required} });\n";
        } else {
            if(isset($relations[$column->name])){
                $relationTableId = Inflector::camel2id($relations[$column->name]['table']);
                $relationModel = new ($easyuiAttributes['modelNamespace'] . Inflector::id2camel($relations[$column->name]['table'], '_'));
                $relationEasyuiAttributes = [];
                if(method_exists($relationModel, 'getEasyuiAttributes')){
                    $relationEasyuiAttributes = $relationModel->easyuiAttributes;
                }

                $perPage = ", 'per-page': 50";
                if(isset($relationEasyuiAttributes['_'])){
                    if(isset($relationEasyuiAttributes['_']['noPagination']) && $relationEasyuiAttributes['_']['noPagination']){
                        $perPage = ", 'per-page': false";
                    }
                }

                $fieldAsText = isset($easyuiAttribute['fieldAsText']) ? $easyuiAttribute['fieldAsText'] : 'name';
                $fieldText .="            {$comment}yii.easyui.refDropdown('{$columnVariablize}', yii.easyui.{$variablize}.{$columnVariablize}Input, () => { }, { editable: true, validType: ['inList[yii.easyui.{$variablize}.{$columnVariablize}Input]'], required: {$required}{$defaultValue}{$panelHeight} });\n";
                $ajaxMapText .="            {$comment}yii.easyui.ajax.map['{$columnVariablize}'] = (callback) => {\n";
                $ajaxMapText .="            {$comment}    return yii.easyui.ajax.ref(callback, '{$columnVariablize}', { r: 'api/v1/{$relationTableId}', fields: '{$relations[$column->name]['column']}, {$fieldAsText}'{$perPage} }, '{$relations[$column->name]['column']}', '{$fieldAsText}');\n";
                $ajaxMapText .="            {$comment}};\n";
                $ajaxMapText .="            {$comment}yii.easyui.ajax.queueCallback['{$columnVariablize}'] = yii.easyui.ajax.queueCallback['{$columnVariablize}'] ?? [];\n";
                $ajaxMapText .="            {$comment}yii.easyui.refLabel['{$columnVariablize}'] = '{$label}';\n";
            } elseif($boolean){
                $fieldText .="            {$comment}yii.easyui.booleanDropdown({$columnVariablize}Input, {label: '{$label}', required: {$required}$defaultValue});\n";
            } else {
                $fieldText .="            {$comment}{$columnVariablize}Input.numberbox({\n";
                $fieldText .="            {$comment}    label: '{$label}',\n";
                $fieldText .="            {$comment}    labelPosition: 'top',\n";
                $fieldText .="            {$comment}    width: 300,\n";
                $fieldText .="            {$comment}    required: {$required},\n$defaultValueNewLine";
                if($column->dbType === 'float8'){
                    $fieldText .="            {$comment}    precision: 2,\n";
                }
                if($isCurrenctyFormat){
                    $fieldText .="            {$comment}    formatter: yii.easyui.formatter.currency,\n";
                    $fieldText .="            {$comment}    parser: yii.easyui.parser.currency,\n";
                }
                $fieldText .="            {$comment}});\n";
            }
        }
    }elseif($column->type == 'boolean'){
        $fieldText .="            {$comment}yii.easyui.booleanDropdown({$columnVariablize}Input, {label: '{$label}', required: {$required}$defaultValue});\n";
    }elseif($isFileInput){
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
        if($acceptFileInput === 'image'){
            $fieldText .="            {$comment}    label: '{$label}',\n";
            $fieldText .="            {$comment}    accept: 'image/*',\n";
            $fieldText .="            {$comment}    buttonText: 'Image File',\n";
        }elseif($acceptFileInput === 'pdf'){
            $fieldText .="            {$comment}    label: '{$label}',\n";
            $fieldText .="            {$comment}    accept: 'pdf/*',\n";
            $fieldText .="            {$comment}    buttonText: 'PDF File',\n";
        }else{
            $fieldText .="            {$comment}    label: '{$label}',\n";
        }
        $fieldText .="            {$comment}    required: {$required}\n";
        $fieldText .="            {$comment}});\n";
    }elseif($column->type == 'date'){
        $fieldText .="            {$comment}{$columnVariablize}Input.datebox({\n";
        $fieldText .="            {$comment}    label: '{$label}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    required: {$required},\n";
        $fieldText .="            {$comment}    validType: [{$validType}],\n$defaultValueNewLine";
        $fieldText .="            {$comment}});\n";
    }elseif($column->type == 'datetime'){
        $fieldText .="            {$comment}{$columnVariablize}Input.datetimebox({\n";
        $fieldText .="            {$comment}    label: '{$label}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        $fieldText .="            {$comment}    required: {$required},\n";
        $fieldText .="            {$comment}    validType: [{$validType}],\n$defaultValueNewLine";
        $fieldText .="            {$comment}});\n";
    }else{
        $fieldText .="            {$comment}{$columnVariablize}Input.textbox({\n";
        $fieldText .="            {$comment}    label: '{$label}',\n";
        $fieldText .="            {$comment}    labelPosition: 'top',\n";
        $fieldText .="            {$comment}    width: 300,\n";
        if($column->size){
            $fieldText .="            {$comment}    validType: ['maxLength[{$column->size}]'],\n";
        }
        $fieldText .="            {$comment}    required: {$required},\n$defaultValueNewLine";
        $fieldText .="            {$comment}});\n";
    }
    $fieldText .="\n";
};

$class = $generator->modelClass;
$pk = $class::primaryKey();

$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        $label = Inflector::camel2words($name);
        if (++$count < 6) {
            //echo "            '" . $name . "',\n";
            $columnText .= "                    { field: '{$name}', title: '{$label}', width: 100 },\n";
        } else {
            //echo "            //'" . $name . "',\n";
            $columnText .= "                    //{ field: '{$name}', title: '{$label}' },\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        if(in_array($column->name, ['created_at', 'created_by', 'updated_at', 'updated_by'])) continue;
        //if (++$count < 6) {
            $fungsi($column, '');
        //} else {
        //    $fungsi($column, '//');
        //}
    }
}

if($haveSortable){
    $resetSort = ",'-',\n";
    $resetSort .= "                {\n";
    $resetSort .= "                    text: 'Reset Sort',\n";
    $resetSort .= "                    iconCls: 'fa-solid fa-filter-circle-xmark',\n";
    $resetSort .= "                    handler: function () {\n";
    $resetSort .= "                        dg.datagrid('resetSort');\n";
    $resetSort .= "                    }\n";
    $resetSort .= "                }\n";
}

$formDialogHeight = 450;
$detail = '';
$treeNavigation = '';
$treeNavigationInit = '';
$treeNavigationInitVar = '';
$treeNavDlgCondition = '';
$treeNavDlgCondition1 = '';
$treeNavDlgCondition2 = '';
$afterSubmitForm1 = "                    dg.datagrid('reload');\n";

if(isset($easyuiAttributes['_'])){
    $formDialogHeight = isset($easyuiAttributes['_']['formDialogHeight'])?$easyuiAttributes['_']['formDialogHeight']:$formDialogHeight;
    
    if(isset($easyuiAttributes['_']['sortName'])){
        $sortGrid = "\n";
        $sortGrid .= "            sortName: '{$easyuiAttributes['_']['sortName']}',\n";

        if(isset($easyuiAttributes['_']['sortOrder'])){
            $sortGrid .= "            sortOrder: '{$easyuiAttributes['_']['sortOrder']}',\n";
        }

        if(isset($easyuiAttributes['_']['remoteSort'])){
            $sortGrid .= "            remoteSort: true,\n";
        }

        if(isset($easyuiAttributes['_']['multiSort']) && $easyuiAttributes['_']['multiSort'] ){
            $sortGrid .= "            multiSort: true,\n";
        }
    }

    if(isset($easyuiAttributes['_']['haveDetail']) && $easyuiAttributes['_']['haveDetail']){

        $detail = ".layout('add', {\n";
        $detail .= "                title: 'Detail',\n";;
        $detail .= "                region: 'east',\n";
        $detail .= "                split: true,\n";
        $detail .= "                border: true,\n";
        $detail .= "                content: '<div id=\"{$id}-east\"></div>',\n";
        $detail .= "                width: '40%'\n";
        $detail .= "            })";

    }

    if(isset($easyuiAttributes['_']['treeNavigation']) && $easyuiAttributes['_']['treeNavigation']){
        $treeNavigationWidth = isset($easyuiAttributes['_']['treeNavigationWidth'])?$easyuiAttributes['_']['treeNavigationWidth']:"'40%'";
        $treeNavigationData = isset($easyuiAttributes['_']['treeNavigationData'])?$easyuiAttributes['_']['treeNavigationData']:$id;
        $treeNavigationFilterField = isset($easyuiAttributes['_']['treeNavigationFilterField'])?$easyuiAttributes['_']['treeNavigationFilterField']:'code';
        $treeNavigationTreeCode = isset($easyuiAttributes['_']['treeNavigationTreeCode'])?$easyuiAttributes['_']['treeNavigationTreeCode']:'code';
        $dataTextHumanize = Inflector::camel2words($treeNavigationData);

        $varText .= "    var treeNav;\n";
        $varText .= "    var selectedTreeNav;\n";
        $varText .= "    var treeData = {};\n";

        $treeNavDlgCondition = "            if (treeData[row.category_id]) {\n";
        $treeNavDlgCondition .= "                selectedTreeNav = treeData[row.category_id];\n";
        $treeNavDlgCondition .= "            } else {\n";
        $treeNavDlgCondition .= "                selectedTreeNav = {\n";
        $treeNavDlgCondition .= "                    id: row.category_id,\n";
        $treeNavDlgCondition .= "                    text: 'Category Name for ID ' + row.category_id\n";
        $treeNavDlgCondition .= "                };\n";
        $treeNavDlgCondition .= "            }\n";
        $treeNavDlgCondition1 = "            if (!selectedTreeNav) {\n";
        $treeNavDlgCondition1 .= "                $.messager.alert('Product Form', 'Please select {$dataTextHumanize} first!', 'warning');\n";
        $treeNavDlgCondition1 .= "                return;\n";
        $treeNavDlgCondition1 .= "            }\n";
        $treeNavDlgCondition1 .= "            if (selectedTreeNav.code === undefined || selectedTreeNav.code.length < 9) {\n";
        $treeNavDlgCondition1 .= "                $.messager.alert('Product Form', 'Please select more detail {$dataTextHumanize}!', 'warning');\n";
        $treeNavDlgCondition1 .= "                return;\n";
        $treeNavDlgCondition1 .= "            }\n";
        
        $treeNavDlgCondition2 = "            row.category_id = selectedTreeNav.id;\n";
        $treeNavDlgCondition2 .= "            row.category_name = selectedTreeNav.text;\n";
        
        $afterSubmitForm1 = "                    treeNavReloadDg(selectedTreeNav);\n";
        $treeNavigation = ".layout('add', {\n";
        //$treeNavigation .= "                title: 'Detail',\n";;
        $treeNavigation .= "                region: 'west',\n";
        $treeNavigation .= "                split: true,\n";
        $treeNavigation .= "                border: true,\n";
        $treeNavigation .= "                content: '<div id=\"{$id}-west\"></div>',\n";
        $treeNavigation .= "                width: {$treeNavigationWidth},\n";
        $treeNavigation .= "            })";

        $treeNavigationInit = '            initTreeNav();' . "\n";

        $treeNavigationInitVar = "    var treeNavReloadDg = function (node) {\n";
        $treeNavigationInitVar .= "        if (node.code) {\n";
        $treeNavigationInitVar .= "            dg.datagrid('addFilterRule', {\n";
        $treeNavigationInitVar .= "                field: '{$treeNavigationFilterField}',\n";
        $treeNavigationInitVar .= "                op: 'contains',\n";
        $treeNavigationInitVar .= "                value: node.{$treeNavigationTreeCode},\n";
        $treeNavigationInitVar .= "                percent: 'suffix'\n";
        $treeNavigationInitVar .= "            });\n";
        $treeNavigationInitVar .= "            dg.datagrid('doFilter');\n";
        $treeNavigationInitVar .= "        } else {\n";
        $treeNavigationInitVar .= "            dg.datagrid('reload');\n";
        $treeNavigationInitVar .= "        }\n";
        $treeNavigationInitVar .= "    };\n";
        $treeNavigationInitVar .= "\n";
        $treeNavigationInitVar .= "    var initTreeNav = function () {\n";
        $treeNavigationInitVar .= "        treeNav = el.find('#{$id}-west');\n";
        $treeNavigationInitVar .= "        treeNav.tree({\n";
        $treeNavigationInitVar .= "            url: yii.easyui.ajaxAuthToken({ r: 'api/v1/{$treeNavigationData}/tree-data' }, true),\n";
        $treeNavigationInitVar .= "            method: 'get',\n";
        $treeNavigationInitVar .= "            onSelect: function (node) {\n"; 
        $treeNavigationInitVar .= "                selectedTreeNav = node;\n";
        $treeNavigationInitVar .= "            },\n";
        $treeNavigationInitVar .= "            onDblClick: function (node) {\n"; 
        $treeNavigationInitVar .= "                treeNav.tree('expand', node.target);\n"; 
        $treeNavigationInitVar .= "                treeNavReloadDg(node);\n";
        $treeNavigationInitVar .= "            },\n";
        $treeNavigationInitVar .= "            onLoadSuccess: function (node, data) {\n"; 
        $treeNavigationInitVar .= "                data.forEach((category) => {\n"; 
        $treeNavigationInitVar .= "                    treeData[category.id] = category;\n";
        $treeNavigationInitVar .= "                });\n";
        $treeNavigationInitVar .= "            }\n";
        $treeNavigationInitVar .= "        });\n";
        $treeNavigationInitVar .= "    }\n";
    }
}
?>

window.yii.easyui.<?= $variablize?> = (function ($) {
    var el;
    var dg;
    var dgOnLoading = false;
    var dlg;
    var frm;<?=$varText?>

    var frmDlg = function (row) {
        var title = 'New <?=$words?>';
        var url;
        if (row) {
            title = 'Edit <?=$words?>';
            url = yii.easyui.ajaxAuthToken({
                r: 'api/v1/<?=$id?>/update',
                id: row.<?=$pk[0]."\n"?>
            }, true);
<?=$treeNavDlgCondition?>
        } else {
            url = yii.easyui.ajaxAuthToken({ r: 'api/v1/<?=$id?>/create' }, true);
<?=$treeNavDlgCondition1?>
        }

        if (dlg) {
            dlg.dialog('setTitle', title);
            dlg.dialog('open');
            frm.form({ url: url });
            frm.form('reset');
            if (!row) {
                row = {};
                row.status_active = 1;
            }
<?=$treeNavDlgCondition2?>
            frm.form('load', row);
        } else {
            row = row || {};
<?=$treeNavDlgCondition2?>
            dlg = $('<div></div>').dialog({
                title: title,
                modal: true,
                height: <?=$formDialogHeight?>,
                width: 355,
                buttons: [
                    {
                        iconCls: 'icon-disk',
                        text: 'Save',
                        handler: function () {
                            $.messager.progress({
                                title: 'Submit',
                                msg: 'Send data to the server...',
                                interval: 0
                            });
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
                iframe: false,
                onSubmit: function () {
                    var isValid = $(this).form('validate');
                    if (!isValid) {
                        $.messager.progress('close');
                    }
                    return isValid;
                },
                success: function (data) {
                    $.messager.progress('close');
                    dlg.dialog('close');
<?=$afterSubmitForm1?>
                    yii.easyui.afterSubmit(data);
                },
                onProgress: function (percent) {
                    let bar = $.messager.progress('bar');
                    bar.progressbar('setValue', percent);
                }
            });

            if (row) {
                frm.form('load', row);
            }
        }
    }

    var initDg = function () {
        dg = el.find('#<?=$id?>-dg');
        dg.datagrid({
            url: yii.easyui.getHost('app'),
            queryParams: yii.easyui.ajaxAuthToken({ r: 'api/v1/jeasyui/<?=$id?>' }),
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
            remoteFilter: true,<?= $sortGrid?>
            columns: [
                [
                    <?= $columnText?>
                ]
            ],
            toolbar: '#<?=$id?>-tb',
            onSelect: function (index, row) {
                yii.easyui.<?= $variablize?>.dgRow = row;
            },
            onBeforeLoad: function (params) {
                if (dgOnLoading === true) {
                    return false;
                }
                dgOnLoading = true;
                return true;
            },
            onLoadSuccess: function (data) {
                dgOnLoading = false;
                $(this).datagrid('selectRow', 0);
                yii.easyui.setCsrfToken(data._meta);
            },
            loadFilter: function (data) {
                data.total = data._meta.totalCount;
                return data;
            },
            onLoadError: function (xhr) {
                dgOnLoading = false;
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
<?=$treeNavigationInitVar?>
    return {
        init: function () {
            el = $('#<?=$id?>-index');
            el.layout({
                fit: true,
                border: false
            })<?=$treeNavigation?><?=$detail?>.layout('add', {
                region: 'center',
                border: true,
                content: '<table id="<?=$id?>-dg"></table>'
            });

<?=$ajaxMapText?>

            initDg();
<?=$treeNavigationInit?>
        },
        frmDlg: frmDlg,
        dg: dg,
        dgRow: null,<?=$publicVar?><?=$previewPdfInit?>
    }
})(jQuery);