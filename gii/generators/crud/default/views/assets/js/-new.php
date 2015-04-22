<?php
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

$modelClassName = StringHelper::basename($generator->modelClass);
$idModelClassName = Inflector::camel2id($modelClassName);
$varModelClassName = Inflector::variablize($modelClassName);

?>
yii.<?=$varModelClassName?>New = (function($) {
    return {
        isActive: false,
        init : function(){
            using(['form','combobox','linkbutton','combogrid','dialog'],function(){
            	
                $('#<?=$idModelClassName?>-new-form').form({
                    success: function (data) {
                        try {
                            data = eval('(' + data + ')');
                            $('#<?=$idModelClassName?>-new-form').form('clear');
                            var listNav = document.getElementById('nav-<?=$idModelClassName?>-list');
                            if($('#maintab').tabs('exists',listNav.dataset.tabtitle)){
                                $('#maintab').tabs('select',listNav.dataset.tabtitle);
                                $('#<?=$idModelClassName?>-index-dg').datagrid('reload');
                                yii.refreshCsrfToken();
                            }
                        } catch (e) {
                            if(typeof data !== 'string'){
                                data = e ;
                            }
                            
                            $('#global-error').dialog({
                                title: 'Error',
                                modal: !0,
                                fit:true,
                                content: data
                            });
                        }
                    }
                });
                
                $('#<?=$idModelClassName?>-save-btn').linkbutton({
            		onClick:function(){
            			$('#<?=$idModelClassName?>-new-form').form('submit');
            		}
            	});
            	 $('#<?=$idModelClassName?>-clear-btn').linkbutton({
            		onClick:function(){
            			$('#<?=$idModelClassName?>-new-form').form('clear');
            		}
            	});
            });
        }
    };
})(jQuery);