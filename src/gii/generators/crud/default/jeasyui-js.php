<?php

/* @var $column \yii\db\Schema */
/**
 * fielAstext
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
 *               'extraAction' => [
 *                   'actionBarcode' => [
 *                       'params' => '$barcode, $queueNumber'
 *                       'return' => 'return \backend\models\Product::allBarcode($barcode, $queueNumber);'
 *                   ]
 *               ],
 *               'detail' => [
 *                    'type' => 'datagrid' //'propertygrid'
 *                    'data' => 'receipt-item-draft'
 *               ],
 *               // disable standar datagrid toolbar button
 *               'dgToolbar' => [
 *                   'create' => false,
 *                   'update' => false
 *                   //'delete' => false
 *               ],
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
$columnText = "\n                    { field: 'ck', checkbox: true },\n";
$filterText = "\n";
$fieldText = "\n";
$varText = "\n";
$ajaxMapText = "\n";
$formText = '<div style="padding: 20px"><form id="' . $id . '-form" method="post"></form></div>';
$privateFunction = "\n";
$sortGrid = "\n";
$resetSort = "\n";
$haveSortable = false;
$publicVar = "";
$layoutVar = "            el = $('#{$id}-index');\n";

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

$showFrmDlg = true;
if(isset($easyuiAttributes['_'])){
    if(isset($easyuiAttributes['_']['dgToolbar']) && isset($easyuiAttributes['_']['dgToolbar']['create']) && $easyuiAttributes['_']['dgToolbar']['create'] === false && 
    isset($easyuiAttributes['_']['dgToolbar']['update']) && $easyuiAttributes['_']['dgToolbar']['update'] === false){
            $showFrmDlg = false;
    }
}

if($showFrmDlg){
    $varText .= "    var dlg;\n    var frm;\n";
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
    &$privateFunction, 
    &$haveSortable,
    &$publicVar,
    &$relations, 
    $labels, 
    $easyuiAttributes,
    $variablize,
    $showFrmDlg
    ) {

    $label = isset($labels[$column->name])?$labels[$column->name]:Inflector::humanize($column->name);
    $columnId = Inflector::camel2id($column->name);
    $columnVariablize = Inflector::variablize($column->name);
    $required = $column->allowNull==1 ? 'false':'true';
    $numberTypes = ['integer', 'smallint', 'double', 'float', 'decimal'];

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
        $privateFunction .="    var pdfPreviewDlg;\n";
        $privateFunction .="    var pdfPreview = function (src) {\n";
        $privateFunction .="        var title = 'PDF Preview';\n";
        $privateFunction .="        if (pdfPreviewDlg) {\n";
        $privateFunction .="            pdfPreviewDlg.dialog('open');\n";
        $privateFunction .="        } else {\n";
        $privateFunction .="            pdfPreviewDlg = $('<div></div>').dialog({\n";
        $privateFunction .="                title: title,\n";
        $privateFunction .="                modal: true,\n";
        $privateFunction .="                height: '80%',\n";
        $privateFunction .="                width: '80%'\n";
        $privateFunction .="            });\n";
        $privateFunction .="        }\n";
        $privateFunction .="        pdfPreviewDlg.dialog({\n";
        $privateFunction .="            content: '<iframe src=\"' + src + '\" style=\"width:100%;height:100%;border:none;\"></iframe>'\n";
        $privateFunction .="        });\n";
        $privateFunction .="    };\n";

        $publicVar .= "        pdfPreview: (url) => {\n";
        $publicVar .= "            return pdfPreview(url);\n";
        $publicVar .= "        },\n";
    }

    if($hideFromGrid === false || $showFrmDlg === true){
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

            $ajaxMapText .="            {$comment}yii.easyui.ajax.map['{$columnVariablize}'] = (callback) => {\n";
            $ajaxMapText .="            {$comment}    return yii.easyui.ajax.ref(callback, '{$columnVariablize}', { r: 'api/v1/{$relationTableId}', fields: '{$relations[$column->name]['column']}, {$fieldAsText}'{$perPage} }, '{$relations[$column->name]['column']}', '{$fieldAsText}');\n";
            $ajaxMapText .="            {$comment}};\n";
            $ajaxMapText .="            {$comment}yii.easyui.ajax.queueCallback['{$columnVariablize}'] = yii.easyui.ajax.queueCallback['{$columnVariablize}'] ?? [];\n";
            $ajaxMapText .="            {$comment}yii.easyui.refLabel['{$columnVariablize}'] = '{$label}';\n";
        }
    }

    //grid
    if($hideFromGrid === false){
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

    if($showFrmDlg === false){
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
        $publicVar .= "        {$columnVariablize}Input: null,\n";
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
                $fieldText .="            {$comment}yii.easyui.refDropdown('{$columnVariablize}', yii.easyui.{$variablize}.{$columnVariablize}Input, () => { }, { editable: true, validType: ['inList[yii.easyui.{$variablize}.{$columnVariablize}Input]'], required: {$required}{$defaultValue}{$panelHeight} });\n";
                
                
            } elseif($boolean){
                $fieldText .="            {$comment}yii.easyui.booleanDropdown({$columnVariablize}Input, { label: '{$label}', required: {$required}$defaultValue });\n";
            } else {
                $fieldText .="            {$comment}{$columnVariablize}Input.numberbox({\n";
                $fieldText .="            {$comment}    label: '{$label}',\n";
                $fieldText .="            {$comment}    labelPosition: 'top',\n";
                $fieldText .="            {$comment}    width: 300,\n";
                $fieldText .="            {$comment}    required: {$required},\n$defaultValueNewLine";
                if($column->type === 'decimal'){
                    $fieldText .="            {$comment}    precision: {$column->scale},\n";
                }
                if($isCurrenctyFormat){
                    $fieldText .="            {$comment}    formatter: yii.easyui.formatter.currency,\n";
                    $fieldText .="            {$comment}    parser: yii.easyui.parser.currency,\n";
                }
                $fieldText .="            {$comment}});\n";
            }
        }
    }elseif($column->type == 'boolean'){
        $fieldText .="            {$comment}yii.easyui.booleanDropdown({$columnVariablize}Input, { label: '{$label}', required: {$required}$defaultValue });\n";
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
        if($column->type == 'text'){
            $fieldText .="            {$comment}    multiline: true,\n";
            $fieldText .="            {$comment}    height: 100,\n";
        }
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
$detailLayout = '';
$treeNavigation = '';
$initAfterLayout = '';
$privateFunction = '';
$treeNavDlgCondition = '';
$treeNavDlgCondition1 = '';
$treeNavDlgCondition2 = '';
$afterSubmitForm1 = "                    dg.datagrid('reload');\n";
$masterSelected = '';

$sortGridFunction = function($easyuiAttributes){
    if(!isset($easyuiAttributes['_']['sortName'])){
        return "";
    }
    $textSort = "\n";
    $textSort .= "            sortName: '{$easyuiAttributes['_']['sortName']}',\n";

    if(isset($easyuiAttributes['_']['sortOrder'])){
        $textSort .= "            sortOrder: '{$easyuiAttributes['_']['sortOrder']}',\n";
    }

    if(isset($easyuiAttributes['_']['remoteSort'])){
        $textSort .= "            remoteSort: true,\n";
    }

    if(isset($easyuiAttributes['_']['multiSort']) && $easyuiAttributes['_']['multiSort'] ){
        $textSort .= "            multiSort: true,\n";
    }

    return $textSort;
};

if(isset($easyuiAttributes['_'])){
    if($showFrmDlg) {
        $formDialogHeight = isset($easyuiAttributes['_']['formDialogHeight'])?$easyuiAttributes['_']['formDialogHeight']:$formDialogHeight;
    }
    $sortGrid = $sortGridFunction($easyuiAttributes);

    if(isset($easyuiAttributes['_']['detail'])){
        $detailWidth = isset($easyuiAttributes['_']['detail']['width']) ? $easyuiAttributes['_']['detail']['width'] : "'40%'";
        $detailLayout = ".layout('add', {\n";
        $detailLayout .= "                title: 'Detail',\n";;
        $detailLayout .= "                region: 'east',\n";
        $detailLayout .= "                split: true,\n";
        $detailLayout .= "                border: true,\n";
        $detailLayout .= "                content: detailDg,\n";
        $detailLayout .= "                width: {$detailWidth},\n";
        $detailLayout .= "            })";

        if(!isset($easyuiAttributes['_']['detail']['type'])){
            $easyuiAttributes['_']['detail']['type'] = 'datagrid';
        }

        if($easyuiAttributes['_']['detail']['type'] === 'datagrid'){
            $masterSelected .= "                detailDg.datagrid({\n";
            $masterSelected .= "                    url: yii.easyui.getHost('app'),\n";
            $masterSelected .= "                    queryParams: yii.easyui.ajaxAuthToken({\n";
            $masterSelected .= "                        r: 'api/v1/jeasyui/{$easyuiAttributes['_']['detail']['data']}',\n";
            $masterSelected .= "                        filterRules: '[{\"field\": \"{$easyuiAttributes['_']['detail']['key']}\", \"value\": ' + row.id + ', \"op\": \"equal\"}]'\n";
            $masterSelected .= "                    }),\n";
            $masterSelected .= "                });\n";

            $detailSchema = $easyuiAttributes['_']['detail']['model']::getTableSchema();
            if($detailSchema){

                $temp_columnText = $columnText; 
                $temp_fieldText = $fieldText; 
                $temp_filterText = $filterText;
                $temp_varText = $varText;
                //$temp_ajaxMapText = $ajaxMapText; 
                $temp_formText = $formText;
                $temp_privateFunction = $privateFunction; 
                $temp_haveSortable = $haveSortable;
                $temp_publicVar = $publicVar;
                $temp_labels = $labels;

                $detailModel = new $easyuiAttributes['_']['detail']['model'];

                $labels = $detailModel->attributeLabels();
                $columnText = '';
                $haveSortable = false;

                foreach($detailSchema->foreignKeys as $k=>$v){
                    $table = '';
                    foreach($v as $kk=>$vv){
                        if($kk == 0 ){
                            $table = $vv;
                        } else {
                            $relations[$kk] = ['table' => $table, 'column' => $vv];
                        }
                    }    
                }

                foreach ($detailSchema->columns as $column) {
                    if($column->name === $easyuiAttributes['_']['detail']['key']){
                        continue;
                    }
                    if(in_array($column->name, ['created_at', 'created_by', 'updated_at', 'updated_by'])) continue;
                    $fungsi($column, '');
                    //} else {
                    //    $fungsi($column, '//');
                    //}
                }

                $detailColumnText = $columnText;

                $columnText = $temp_columnText; 
                $fieldText = $temp_fieldText; 
                $filterText = $temp_filterText;
                $varText = $temp_varText;
                //$ajaxMapText = $temp_ajaxMapText; 
                $formText = $temp_formText;
                $privateFunction = $temp_privateFunction; 
                $haveSortable = $temp_haveSortable;
                $publicVar = $temp_publicVar;
                $labels = $temp_labels;

                if(method_exists($detailModel, 'getEasyuiAttributes')){
                    $detailEasyuiAttributes = $detailModel->easyuiAttributes;
                } else {
                    $detailEasyuiAttributes = [];
                }
                
                $detailSortGrid = $sortGridFunction($detailEasyuiAttributes);

                $varText .= "    var detailDg;\n";
                $layoutVar .= "            detailDg = $('<div id=\"{$id}-detail-dg\"></div>');\n";
                $initAfterLayout .= '            initDetailDg();' . "\n";
                $privateFunction .= "    var initDetailDg = function () {\n";
                $privateFunction .= "        detailDg.datagrid({\n";
                $privateFunction .= "            method: 'get',\n";
                $privateFunction .= "            fit: true,\n";
                $privateFunction .= "            border: false,\n";
                $privateFunction .= "            striped: true,\n";
                $privateFunction .= "            singleSelect: true,\n";
                $privateFunction .= "            emptyMsg: 'No Records Found.',\n";
                $privateFunction .= "            rownumbers: true,\n";
                $privateFunction .= "            pagination: true,\n";
                $privateFunction .= "            pageSize: 50,\n";
                $privateFunction .= "            remoteFilter: true,\n";
                if($detailSortGrid){
                    $privateFunction .= "            {$detailSortGrid}";
                }
                $privateFunction .= "            columns: [\n";
                $privateFunction .= "                [\n";
                $privateFunction .= $detailColumnText;
                $privateFunction .= "                ]\n";
                $privateFunction .= "            ]\n";
                $privateFunction .= "        });\n";
                $privateFunction .= "    }\n";

            }
        }
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
        
        //$afterSubmitForm1 = "                    treeNavReloadDg(selectedTreeNav);\n"; //error banyak loading
        $treeNavigation = ".layout('add', {\n";
        //$treeNavigation .= "                title: 'Detail',\n";;
        $treeNavigation .= "                region: 'west',\n";
        $treeNavigation .= "                split: true,\n";
        $treeNavigation .= "                border: true,\n";
        $treeNavigation .= "                content: '<div id=\"{$id}-west\"></div>',\n";
        $treeNavigation .= "                width: {$treeNavigationWidth},\n";
        $treeNavigation .= "            })";

        $initAfterLayout = '            initTreeNav();' . "\n";

        $privateFunction .= "\n";
        $privateFunction .= "    var treeNavReloadDg = function (node) {\n";
        $privateFunction .= "        dg.datagrid('addFilterRule', {\n";
        $privateFunction .= "            field: '{$treeNavigationFilterField}',\n";
        $privateFunction .= "            op: 'contains',\n";
        $privateFunction .= "            value: node.{$treeNavigationTreeCode},\n";
        $privateFunction .= "            percent: 'suffix'\n";
        $privateFunction .= "        });\n";
        $privateFunction .= "        dg.datagrid('doFilter');\n";
        $privateFunction .= "    };\n";
        $privateFunction .= "\n";
        $privateFunction .= "    var initTreeNav = function () {\n";
        $privateFunction .= "        treeNav = el.find('#{$id}-west');\n";
        $privateFunction .= "        treeNav.tree({\n";
        $privateFunction .= "            url: yii.easyui.ajaxAuthToken({ r: 'api/v1/{$treeNavigationData}/tree-data' }, true),\n";
        $privateFunction .= "            method: 'get',\n";
        $privateFunction .= "            onSelect: function (node) {\n"; 
        $privateFunction .= "                selectedTreeNav = node;\n";
        $privateFunction .= "            },\n";
        $privateFunction .= "            onDblClick: function (node) {\n"; 
        $privateFunction .= "                treeNav.tree('expand', node.target);\n"; 
        $privateFunction .= "                treeNavReloadDg(node);\n";
        $privateFunction .= "            },\n";
        $privateFunction .= "            onLoadSuccess: function (node, data) {\n"; 
        $privateFunction .= "                data.forEach((category) => {\n"; 
        $privateFunction .= "                    treeData[category.id] = category;\n";
        $privateFunction .= "                });\n";
        $privateFunction .= "            }\n";
        $privateFunction .= "        });\n";
        $privateFunction .= "    }\n";
    }

    if(isset($easyuiAttributes['_']['extraJsFunction']) ){
        foreach($easyuiAttributes['_']['extraJsFunction'] as $k => $v){
            $privateFunction .= "\n";
            $privateFunction .= $v['privateFunction'];
            $publicVar .= '        '.$k . ': ' . $v['publicVar'] ."\n";
        }
    }
}

?>

window.yii.easyui.<?= $variablize?> = (function ($) {
    var el;
    var dg;
    var dgOnLoading = false;<?=$varText?>
<?php if($showFrmDlg):?>

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
<?php endif;?>

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
            remoteFilter: true,
<?= $sortGrid?>
            columns: [
                [<?= $columnText?>
                ]
            ],
            toolbar: '#<?=$id?>-tb',
            onSelect: function (index, row) {
                yii.easyui.<?= $variablize?>.dgRow = row;
<?= $masterSelected ?>
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
<?=$privateFunction?>

    return {
        init: function () {
<?=$layoutVar?>
            el.layout({
                fit: true,
                border: false
            })<?=$treeNavigation?><?=$detailLayout?>.layout('add', {
                region: 'center',
                border: true,
                content: '<table id="<?=$id?>-dg"></table>'
            });

<?=$ajaxMapText?>

            initDg();
<?=$initAfterLayout?>
        },
<?php if($showFrmDlg):?>
        frmDlg: frmDlg,
<?php endif;?>
        dg: dg,
        dgRow: null,
<?=$publicVar?>
    }
})(jQuery);