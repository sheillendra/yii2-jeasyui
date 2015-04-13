<?php
use yii\helpers\StringHelper;

$modelName = StringHelper::basename($generator->modelClass);
$lowModelName = strtolower($modelName);

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
$labels = $model->attributeLabels();

?>
yii.<?=$lowModelName?>Index = (function($) {
    return {
        isActive: false,
        init : function(){
            using(['datagrid','linkbutton','menubutton','propertygrid','validatebox'],function(){
                $('#<?=$lowModelName?>-index-new-btn').linkbutton({
                    text:'New',
                    iconCls:'icon-add',
                    plain:!0,
                    onClick:function(){
                        yii.app.createTab('<?=$modelName?> New','/<?=$lowModelName?>/new','ia-icon-form','nav-<?=$lowModelName?>-new')
                    }
                });
                $('#<?=$lowModelName?>-index-del-btn').linkbutton({
                    text:'Delete',
                    iconCls:'icon-cross',
                    plain:!0,
                    onClick:function(){
                        var checked =  $('#<?=$lowModelName?>-index-dg').datagrid('getChecked'),
                            ids=[];
                    
                        if(!checked.length){
                            $.messager.alert('Error','Pilih data yang akan di hapus','error');
                            return;
                        }
                        $.each(checked,function(k,v){
                            ids.push(v.id);
                        });
                        $.ajax({
                            url:'/<?=$lowModelName?>/delete',
                            type:'post',
                            data:{ids:ids},
                            success:function(r){
                                $('#<?=$lowModelName?>-index-dg').datagrid('reload');
                            }
                        });
                    }
                });
                
                $('#<?=$lowModelName?>-index-dg').datagrid({
                    border:!1,
                    singleSelect:!0,
                    checkOnSelect:!1,
                    selectOnCheck:!1,
                    fit:!0,
                    url:'/<?=$lowModelName?>/get-list-data',
                    pagination:!0,
                    method:'get',
                    columns:[[
                        <?php
                        $text=[];
                        $text[]='{field:"ck",checkbox:!0}';
                        $count = 0;
                        if (($tableSchema = $generator->getTableSchema()) === false) {
                            foreach ($generator->getColumnNames() as $name) {
                                if($count > 6){
                                    $text[]="//{field:'$name',title:''}";
                                }else{
                                    $text[]="{field:'$name',title:''}";
                                }
                                $count++;
                            }
                        }else{
                            foreach ($tableSchema->columns as $column) {
                                if($count > 6){
                                    $text[]="//{field:'$column->name',title:'".$labels[$column->name]."'}";
                                }else{
                                    $text[]="{field:'$column->name',title:'".$labels[$column->name]."'}";
                                }
                                $count++;
                            }
                        }
                        echo implode(",\n                        ",$text)."\n";
                        ?>
                    ]],
                    onSelect:function(i,row){
                        $('#<?=$lowModelName?>-index-pg').propertygrid({
                            data:[
                                {
                                    name:'No.',
                                    value:row.asset_no,
                                    group:'Asset',
                                    editor:''
                                },
                                {
                                    name:'Note',
                                    value:row.asset_note,
                                    group:'Asset',
                                    editor:''
                                }
                            ]
                        });
                    },
                    onLoadSuccess:function(data){
                        yii.app.showGridMsg(this,data);
                        $(this).datagrid('selectRow',0);
                    }
                });
                
                $('#<?=$lowModelName?>-index-pg').propertygrid({
                    fit:!0,
                    border:!1,
                    showGroup:!0,
                    showHeader:!1
                });
            });
        }
    };
})(jQuery);