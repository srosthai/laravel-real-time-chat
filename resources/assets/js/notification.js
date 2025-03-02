let totalNotification = 0;

const mediaTypeImage = 1;
const mediaTypePdf = 2;
const mediaTypeDocx = 3;
const mediaTypeVoice = 4;
const mediaTypeVideo = 5;
const mediaTypeTxt = 7;
const mediaTypeXls = 8;

window.getNotificationMsgTime = function (dateTime, format = 'h:mma') {
    return moment.utc(dateTime).local().format(format);
};

window.hideNoNotification = function () {
    $('#noNewNotification').hide();
};

window.showNoNotification = function () {
    $('#noNewNotification').show();
};

window.setTotalNotificationCount = function (totalNotification) {
    $('.totalNotificationCount').
        text(totalNotification).
        attr('data-total_count', totalNotification).show();

    if (totalNotification > 0) {
        $('.read-all-notification').show();
        $('.totalNotificationCount').show();
        hideNoNotification();
    } else {
        $('.read-all-notification').hide();
        $('.totalNotificationCount').hide();
        showNoNotification();
    }
};

window.getMessageByItsTypeForNotificationList = function (
    message, message_type, file_name = '') {
    if (message_type === mediaTypeImage) {
        return '<i class="fa fa-camera mx-0 text-left" aria-hidden="true"></i>' +
            ' Photo';
    } else if (message_type === mediaTypePdf) {
        return '<i class="fa fa-file-pdf-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' +
            file_name;
    } else if (message_type === mediaTypeDocx) {
        return '<i class="fa fa-file-word-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' + file_name;
    } else if (message_type === mediaTypeVoice) {
        return '<i class="fa fa-file-audio-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' + file_name;
    } else if (message_type === mediaTypeVideo) {
        return '<i class="fa fa-file-video-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' + file_name;
    } else if (message_type === mediaTypeTxt) {
        return '<i class="fa fa-file-text-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' + file_name;
    } else if (message_type === mediaTypeXls) {
        return '<i class="fa fa-file-excel-o mx-0 text-left" aria-hidden="true"></i>' +
            ' ' + file_name;
    } else {
        return message;
    }
};

window.renderNotifications = function () {
    let template = $.templates('#tmplNotification');
    let myHelpers = {
        getNotificationMsgTime: getNotificationMsgTime,
        getMessageByItsTypeForNotificationList: getMessageByItsTypeForNotificationList,
    };
    let notificationHtml = template.render(notifications, myHelpers);

    totalNotification = notifications.length;
    setTotalNotificationCount(totalNotification);

    $('.notification__popup').append(notificationHtml);
};

renderNotifications();

window.Echo.private(`user.${loggedInUserId}`).
    listen('UserEvent', (notification) => {
        if (notification.type !== 6) { // message deleted for everyone
            return false;
        }
        let currentUrl = window.location.href;
        if (currentUrl.indexOf(conversationsURL) != -1 &&
            (getCurrentUserId() === notification.owner_id)) {
            return false;
        }
        hideNoNotification();
        let isUserElePresent = $('.notification__popup').
            find('#owner-' + notification.owner_id).length;
        let myHelpers = {
            getNotificationMsgTime: getNotificationMsgTime,
            getMessageByItsTypeForNotificationList: getMessageByItsTypeForNotificationList,
        };
        if (isUserElePresent) {
            let template = $.templates('#tmplOldNotification');
            let notificationHtml = template.render(notification, myHelpers);
            $('#owner-' + notification.owner_id).html(notificationHtml);
        } else {
            totalNotification += 1;
            setTotalNotificationCount(totalNotification);
            let template = $.templates('#tmplNotification');
            let notificationHtml = template.render(notification, myHelpers);
            $('.notification__popup').append(notificationHtml);
        }
        notificationSound();
    });

$(document).on('click', '.notification-item', function (e) {
    e.preventDefault();
    let notificationId = $(this).data('notification_id');
    let selector = $(this).attr('id');

    readNotification(notificationId, '#' + selector);
});

window.readNotification = function (notificationId, selector) {
    $.ajax({
        type: 'get',
        url: route('notification.read-notification',notificationId),
        success: function (data) {
            totalNotification = totalNotification - 1;
            setTotalNotificationCount(totalNotification);
            let ownerId = data.data.owner_id;

            $(selector).remove();
            displayToastr('Success', 'success', data.message);

            let currentUrl = window.location.href;
            if (currentUrl.indexOf(conversationsURL) != -1) {
                let conversationEle = $(document).find('#user-' + ownerId);
                conversationEle.trigger('click');
            } else {
                window.location.replace(
                    conversationsURL + '?conversationId=' + ownerId);
            }
        },
        error: function (result) {
            displayToastr('Error', 'error',
                result.responseJSON.message);
        },
    });
};

window.readNotificationWhenChatWindowOpen = function (
    notificationId, selector) {
    $.ajax({
        type: 'get',
        url: route('notification.read-notification',notificationId),
        success: function () {
            $(selector).remove();
            let notifications = $('.notification__popup').
                find('.notification-item').length;
            totalNotification = notifications;
            setTotalNotificationCount(totalNotification);
        },
        error: function (result) {
            displayToastr('Error', 'error',
                result.responseJSON.message);
        },
    });
};

window.getCurrentUserId = function () {
    return $('.chat__person-box--active').data('id');
};

window.readNotificationWhenOpenChatWindow = function (senderId) {
    $('#owner-' + senderId).remove();
    let notifications = $('.notification__popup').
        find('.notification-item').length;
    totalNotification = notifications;
    setTotalNotificationCount(totalNotification);
};

$(document).on('click', '.read-all-notification', function (e) {
    e.preventDefault();
    if (!totalNotification > 0) {
        return false;
    }
    readAllNotification();
});

window.readAllNotification = function (notificationId, selector) {
    $.ajax({
        type: 'get',
        url: route('read-all-notification',notificationId),
        success: function (data) {
            let senderIds = data.data.sender_ids;
            $.each(senderIds, (index, val) => {
                $("#user-"+val).find('.chat__person-box-count').text(0).hide();
            });
            totalNotification = 0;
            setTotalNotificationCount(totalNotification);

            $('.notification__popup').find('.notification-item').remove();
            displayToastr('Success', 'success', data.message);
        },
        error: function (result) {
            displayToastr('Error', 'error',
                result.responseJSON.message);
        },
    });
};
