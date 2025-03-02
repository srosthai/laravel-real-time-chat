window.setLastSeenOfUser = function (status) {
    $.ajax({
        type: 'post',
        url: route('update-last-seen'),
        data: { status: status },
        success: function (data) {
        },
    });
};

//set user status online
setLastSeenOfUser(1);

window.onbeforeunload = function () {
    Echo.leave('user-status');
    setLastSeenOfUser(0);
    //return undefined; to prevent dialog while window.onbeforeunload
    return undefined;
};

Echo.join(`user-status`);
