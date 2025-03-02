<script id="tmplNotification" type="text/x-jsrender">
    <a class="dropdown-item chat__person-box unread notification-item" data-notification_id="{{:id}}" id="owner-{{:owner_id}}" href="#">
        <span class="profile-image">
            <img src="{{:senderUser.profile_url}}">
        </span>
        <div class="description">
            <div><b>{{:senderUser.name}}</b></div>
            <span>{{:~getMessageByItsTypeForNotificationList(notification, message_type, file_name)}}</span>
        </div>
        <div class="notification__info">
            <div class="notification__info-time">{{:~getNotificationMsgTime(created_at)}}</div>
            <div class="notification__info-count">{{:unread_count}}</div>
            <span class="notification__info-read d-none"><i class="fa fa-check"></i></span>
        </div>
    </a>



</script>

<script id="tmplOldNotification" type="text/x-jsrender">
    <span class="profile-image">
        <img src="{{:senderUser.profile_url}}">
    </span>
    <div class="description">
        <div><b>{{:senderUser.name}}</b></div>
        <span>{{:~getMessageByItsTypeForNotificationList(notification, message_type, file_name)}}</span>
    </div>
    <div class="notification__info">
        <div class="notification__info-time">{{:~getNotificationMsgTime(created_at)}}</div>
        <div class="notification__info-count">{{:unread_count}}</div>
    </div>



</script>
