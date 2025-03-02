function loadEmoji () {
    $('#userStatusEmoji').emojioneArea({
        standalone: true,
        autocomplete: false,
        saveEmojisAs: 'shortname',
        pickerPosition: 'right',
    });
}

loadEmoji();

$(document).on('click', '#setUserStatus', function (e) {
    e.preventDefault();
    let loadingButton = $(this);
    loadingButton.button('loading');
    
    let emojiShortName = $('#userStatusEmoji').
        data('emojioneArea').
        getText().
        trim();
    let emoji = emojione.shortnameToImage(emojiShortName);
    let data = {
        'emoji': emoji,
        'emoji_short_name': emojiShortName,
        'status': $('#userStatus').val(),
    };

    $.ajax({
        type: 'post',
        url: route('set-user-status'),
        data: data,
        success: function (data) {
            displayToastr('Success', 'success', data.message);
            loadingButton.button('reset');
            $('#setCustomStatusModal').modal('hide');
        },
        error: function (result) {
            displayToastr('Error', 'error',
                result.responseJSON.message);
            loadingButton.button('reset');
        },
    });
});

$(document).on('click', '#clearUserStatus', function (e) {
    e.preventDefault();
    let loadingButton = $(this);
    loadingButton.button('loading');
    
    $.ajax({
        type: 'get',
        url: route('clear-user-status'),
        success: function (data) {
            $('#userStatus').val('');
            $('#userStatusEmoji')[0].emojioneArea.setText('');
            displayToastr('Success', 'success', data.message);
            loadingButton.button('reset');
            $('#setCustomStatusModal').modal('hide');
        },
        error: function (result) {
            displayToastr('Error', 'error',
                result.responseJSON.message);
            loadingButton.button('reset');
        },
    });
});

if (loggedInUserStatus != '' && loggedInUserStatus.hasOwnProperty('status')) {
    $('#userStatus').val(loggedInUserStatus.status);
    $('#userStatusEmoji')[0].emojioneArea.setText(
        loggedInUserStatus.emoji_short_name);
}
