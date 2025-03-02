window.displayToastr = function (heading, icon, message) {
    $.toast({
        heading: heading,
        text: message,
        showHideTransition: 'slide',
        icon: icon,
        position: 'top-right',
    });
};

window.htmlSpecialCharsDecode = function (string) {
    return jQuery('<div />').html(string).text();
};

window.setLocalStorageItem = function (variable, data) {
    localStorage.setItem(variable, data);
};

window.getLocalStorageItem = function (variable) {
    return localStorage.getItem(variable);
};

window.removeLocalStorageItem = function (variable) {
    localStorage.removeItem(variable);
};

/** Change Password */
$('#changePasswordForm').on('submit', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnEditSave');

    loadingButton.button('loading');
    $.ajax({
        url: route('change-password'),
        type: 'POST',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displayToastr('Success', 'success', result.message);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        },
        error: function (result) {
            displayToastr('Error', 'error', result.responseJSON.message);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

window.avoidSpace = function (event) {
    var k = event ? event.which : window.event.keyCode;
    if (k === 32) {
        event.stopPropagation();
        return false;
    }
};

window.resetModalForm = function (formId, validationBox) {
    $(formId)[0].reset();
    $(validationBox).hide();
};

window.deleteItemAjax = function (url, tableId, header, callFunction = null) {
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        success: function (obj) {
            if (obj.success) {
                if ($(tableId).DataTable().data().count() == 1) {
                    $(tableId).DataTable().page('previous').draw('page')
                } else {
                    $(tableId).DataTable().ajax.reload(null, false)
                }
            }
            displayToastr('Success', 'success',
                header + ' has been deleted.')
        },
        error: function (obj) {
            displayToastr('Error', 'error', obj.responseJSON.message)
        },
    });
};

window.notificationSound = function() {
    let sound = document.getElementById("notificationSound");
    sound.currentTime = 0;
    sound.play();
}
