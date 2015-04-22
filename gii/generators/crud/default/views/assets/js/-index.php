<?php
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
$labels = $model->attributeLabels();

$class = $generator->modelClass;
$pks = $class::primaryKey();
if(count($pks)){
    $pks = $pks[0];
}else{
    $pks = 'id';
}
?>
yii.<?=$varModelClassName?>Index = (function($) {
    return {
        isActive: false,
        init : function(){
            using(['datagrid','linkbutton','menubutton','propertygrid','validatebox'],function(){
                $('#<?=$idModelClassName?>-index-new-btn').linkbutton({
                    text:'New',
                    iconCls:'icon-add',
                    plain:!0,
                    onClick:function(){
                        yii.app.createTab('<?=$modelClassName?> New',yii.<?=$varModelClassName?>Index.newUrl,'ia-icon-form','nav-<?=$idModelClassName?>-new')
                    }
                });
                $('#<?=$idModelClassName?>-index-del-btn').linkbutton({
                    text:'Delete',
                    iconCls:'icon-remove',
                    plain:!0,
                    onClick:function(){
                        var checked =  $('#<?=$idModelClassName?>-index-dg').datagrid('getChecked'),
                            ids=[];
                    
                        if(!checked.length){
                            $.messager.alert('Error','Pilih data yang akan di hapus','error');
                            return;
                        }
                        $.each(checked,function(k,v){
                            ids.push(v.<?=$pks?>);
                        });
                        $.ajax({
                            url:yii.<?=$varModelClassName?>Index.deleteUrl,
                            type:'post',
                            data:{ids:ids},
                            success:function(r){
                                $('#<?=$idModelClassName?>-index-dg').datagrid('reload');
                            }
                        });
                    }
                });
                
                $('#<?=$idModelClassName?>-index-dg').datagrid({
                    border:!1,
                    singleSelect:!0,
                    checkOnSelect:!1,
                    selectOnCheck:!1,
                    fit:!0,
                    url:yii.<?=$varModelClassName?>Index.getListDataUrl,
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
                        $('#<?=$idModelClassName?>-index-pg').propertygrid({
                            data:[
                                <?php
                                    $text=[];
                                    $count = 0;
                                    if (($tableSchema = $generator->getTableSchema()) !== false) {
                                        foreach ($tableSchema->columns as $column) {
                                            if($count > 10){
                                                $text[]="//{name:'".$labels[$column->name]."',value:row.".$column->name.",group:'Detail Group',editor:''}";
                                            }else{
                                                $text[]="{name:'".$labels[$column->name]."',value:row.".$column->name.",group:'Detail Group',editor:''}";
                                            }
                                            $count++;
                                        }
                                    }
                                    echo implode(",\n                                ",$text)."\n";
                                ?>
                            ]
                        });
                    },
                    onLoadSuccess:function(data){
                        yii.app.showGridMsg(this,data);
                        $(this).datagrid('selectRow',0);
                    }
                });
                
                $('#<?=$idModelClassName?>-index-pg').propertygrid({
                    fit:!0,
                    border:!1,
                    showGroup:!0,
                    showHeader:!1
                });
            });
        }
    };
})(jQuery);