yii.login = (function ($) {

    return {
        isActive: false,
        init: function () {
            using(['dialog', 'form', 'textbox', 'linkbutton', 'messager'], function () {

                $('#login-dialog').dialog({
                    title: 'Login',
                    width: 350,
                    content: yii.login.content,
                    closable: !1,
                    minimizable: !1,
                    maximizable: !1,
                    collapsible: !1,
                    draggable: !1,
                    resizable: !1,
                    iconCls: 'icon-lock',
                    buttons: '#login-btn'
                });

                $('#loginform-username').textbox({
                    prompt: 'Username',
                    required: 1
                });

                $('#loginform-password').textbox({
                    prompt: 'Password',
                    required: 1
                });

                $('#login-form').form({
                    url: yii.login.url,
                    novalidate: true,
                    onSubmit: function () {
                        $(this).form('enableValidation')
                        if (!$(this).form('validate')) {
                            return false;
                        }
                    },
                    success: function (data) {
                        try {
                            data = eval('(' + data + ')');

                            if (typeof data.redirect !== 'undefined') {
                                window.location = data.redirect;
                                return;
                            }

                            if (typeof data.loginerror !== 'undefined') {
                                $('#login-message').empty();
                                $.each(data.loginerror, function (k, v) {
                                    if (typeof v === 'object') {
                                        $.each(v, function (kk, vv) {
                                            $('#login-message').append(vv);
                                        });
                                    }
                                });
                                return;
                            }
                        } catch (e) {
                            $('#error').dialog({
                                title: 'Error',
                                modal: !0,
                                width: '90%',
                                height: 500,
                                content: data
                            });
                        }
                    }
                });

                $('#login-btn-ok').linkbutton({
                    iconCls: 'icon-ok',
                    text: 'Login',
                    onClick: function () {
                        $('#login-form').form('submit');
                    }
                });

                $('#login-btn-reset').linkbutton({
                    iconCls: 'icon-cancel',
                    text: 'Reset',
                    onClick: function () {
                        $('#login-form').form('clear');
                    }
                });

                $('#loginform-username').textbox('clear').textbox('textbox').focus();
                $(document).on('click', '#login-signup-btn', function (e) {
                    if (!document.getElementById('signup-dialog')) {
                        $.ajax({
                            url: '/user/signup',
                            success: function (r) {
                                $('body').append(r);
                                yii.signup.init();
                            }
                        });
                    } else {
                        var signupDlg = $('#signup-dialog');
                        if (signupDlg.dialog('window').css('display') === "none") {
                            signupDlg.dialog('open');
                        }
                    }
                    e.stopImmediatePropagation();
                });
            });
        },
        content: ''
    };
})(jQuery);