window.yii.profile = (function ($) {
    var profilePasswordForm;
    var oldPasswordInput;
    var newPasswordInput;
    var repeatPasswordInput;
    var submitProfilePasswordForm;

    return {
        isActive: false,
        init: function () {
            var profileEl = $('#profile-index');
            profileEl.layout({
                fit: true,
                border: false
            }).layout('add', {
                region: 'center',
                border: true,
                content: '<div id="profile-center"></div>'
            });

            var profileTab = $('#profile-center').tabs({
                tabPosition: 'left',
                border: false,
                fit: true
            });
            
            profilePasswordForm = $('<form id="eselon-form" method="post" style="padding: 10px"></form>');
            profileTab.tabs('add', {
                title: 'Ganti Password',
                content: profilePasswordForm,
                selected: true,
            });

            profileTab.tabs('add', {
                title: 'Foto',
                content: '<div style="padding: 10px">On Development</div>',
            });

            oldPasswordInput = $('<input id="old-password" name="oldPassword"/>');
            profilePasswordForm.append(oldPasswordInput);
            oldPasswordInput.passwordbox({
                label: 'Current Password: ',
                labelPosition: 'top',
                width: 300,
                required: true
            });

            newPasswordInput = $('<input id="new-password" name="newPassword"/>');
            profilePasswordForm.append(newPasswordInput);
            newPasswordInput.passwordbox({
                label: 'New Password: ',
                labelPosition: 'top',
                width: 300,
                required: true,
                validType: "diffTo['#old-password']"
            });

            repeatPasswordInput = $('<input name="repeatPassword"/>');
            profilePasswordForm.append(repeatPasswordInput);
            repeatPasswordInput.passwordbox({
                label: 'Repeat New Password: ',
                labelPosition: 'top',
                width: 300,
                required: true,
                validType: "equalTo['#new-password']"
            });

            profilePasswordForm.append('<br><br>');
            submitProfilePasswordForm = $('<div></div>');
            profilePasswordForm.append(submitProfilePasswordForm);

            submitProfilePasswordForm.linkbutton({
                iconCls: 'icon-disk',
                text: 'Save',
                onClick: function () {
                    yii.easyui.showMainMask();
                    profilePasswordForm.form('submit');
                }
            });

            profilePasswordForm.form({
                url: yii.easyui.ajaxAuthToken({
                    r: 'jeasyui/profile/change-password',
                }, true),
                iframe: false,
                success: function (data) {
                    yii.easyui.hideMainMask();
                    data = JSON.parse(data);
                    if(data.success){
                        window.location.reload();
                    } else {
                        $.messager.alert('Change Password', data.message, 'error');
                    }
                },
                onSubmit: function (params) {
                    var isValid = $(this).form('validate');
                    if (!isValid) {
                        yii.easyui.hideMainMask();	// hide progress bar while the form is invalid
                    }
                    return isValid;
                }
            });

        }
    };
})(window.jQuery);