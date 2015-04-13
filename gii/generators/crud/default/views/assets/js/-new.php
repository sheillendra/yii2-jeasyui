<?php
use yii\helpers\StringHelper;

$modelName = StringHelper::basename($generator->modelClass);
$lowModelName = strtolower($modelName);

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
$labels = $model->attributeLabels();

?>
yii.<?=$lowModelName?>New = (function($) {
    return {
        isActive: false,
        init : function(){
            using(['form','combobox','linkbutton','combogrid'],function(){
            	
                $('#<?=$lowModelName?>-new-form').form({
                    success: function (data) {
                        try {
                            data = eval('(' + data + ')');
                            $('#<?=$lowModelName?>-new-form').form('clear');
                            var listNav = document.getElementById('nav-<?=$lowModelName?>-list');
                            if($('#maintab').tabs('exists',listNav.dataset.tabtitle)){
                                $('#maintab').tabs('select',listNav.dataset.tabtitle);
                                $('#<?=$lowModelName?>-index-dg').datagrid('reload');
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
                
                $('#<?=$lowModelName?>-save-btn').linkbutton({
            		onClick:function(){
            			$('#<?=$lowModelName?>-new-form').form('submit');
            		}
            	});
            	 $('#<?=$lowModelName?>-clear-btn').linkbutton({
            		onClick:function(){
            			$('#<?=$lowModelName?>-new-form').form('clear');
            		}
            	});
            });
        }
    };
})(jQuery);