yii.signup = (function($) {

    return {
        isActive: false,
        init: function() {
            using(['dialog', 'form','textbox','linkbutton'], function() {

                $('#signup-dialog').dialog({
                    title:'Sign Up',
                    width: 350,
                    modal: true,
                    content:yii.signup.content,
                    buttons:'#signup-btn'
                });
                
                $('#signupform-username').textbox({required:1});
                $('#signupform-email').textbox({
                    required:1,
                    validType:'email'
                });
                $('#signupform-password').textbox({
                    required:1,
                    validateType:'length[6,20]'
                });
                $('#signup-form').form({
                    url:yii.signup.url,
                    novalidate:true,
                    onSubmit:function(){
                        $(this).form('enableValidation')
                        if(!$(this).form('validate')){
                            return false;
                        }
                    },
                    success:function(data){
                        data = eval('('+data+')');
                        console.log(data);
                    }
                });
                
                $('#signup-btn-ok').linkbutton({
                    iconCls:'icon-ok',
                    text:'Login',
                    onClick:function(){
                        $('#signup-form').form('submit');
                    }
                });
                
                $('#signup-btn-reset').linkbutton({
                    iconCls:'icon-cancel',
                    text:'Reset',
                    onClick:function(){
                        $('#signup-form').form('clear');
                    }
                });
                
                $('#signupform-username').textbox('textbox').focus();
            });
        },
        content:''
    };
})(jQuery);