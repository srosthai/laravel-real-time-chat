<script id="tmplMessage" type="text/x-jsrender">
        <div id="send-receive-direction" class="{{:className}} message-{{:data.id}}" data-message_id="{{:data.id}}">
            <div class="chat-conversation__avatar">
            {{if data.sender.role_name == 'Admin'}}
            <span class="highlighter" data-bs-toggle="tooltip" title="Admin"><img src="./assets/images/crown.svg"></span>
            {{/if}}
             {{if isReceiver}}
                <img src="{{:data.sender.photo_url}}" alt="" class="img-fluid conversation-user-img user-chat-image-{{:data.sender.id}}"
                     {{if data.is_group}} data-bs-toggle="tooltip" data-bs-placement="top" title="{{:data.sender.name}}" {{/if}}>
             {{else}}
              <img src="{{:authImage}}" alt="" class="img-fluid conversation-user-img"
                     {{if data.is_group}} data-bs-toggle="tooltip" data-bs-placement="top" title="{{:authUserName}}" {{/if}}>
             {{/if}}
            </div>
            {{if !isReceiver}}
                <div class="chat-conversation__menu d-flex align-items-center">
                    <div class="dropdown btn-group hide-ele msg-options">
                        <i class="fa fa-ellipsis-v " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        <div class="dropdown-menu chat-replay-dropdown">
                            {{if allowToDelete}}
                                <a class="dropdown-item msg-delete-icon" href="#">Delete Message</a>
                            {{/if}}
                            {{if deleteMsgForEveryone}}
                                <a class="dropdown-item msg-delete-for-everyone" href="#">Delete For Everyone</a>
                            {{/if}}
                             <a class="dropdown-item msg-replay" href="#" data-self-reply="1" data-message-id="{{:data.id}}" data-message='{{:data.message}}' data-message-type='{{:data.message_type}}'>Reply</a>
                             <a class="dropdown-item open-msg-info" data-message-id="{{:data.id}}" data-is_group="{{:data.is_group}}">
                                Info
                             </a>
                        </div>
                    </div>
                </div>
            {{/if}}
            <div class="chat-conversation__bubble clearfix {{if data.message_type !== 0 && isReceiver}} bubble-border-green {{/if}}" >
                {{if data.reply_to }}
                    <span class="person-name-texture {{if isReceiver}} text-white {{/if}}">{{if loggedInUserId == data.reply_message.sender.id}} You {{else}} {{:data.reply_message.sender.name}} {{/if}}</span>
                    <p class="m-1 px-2">{{:~displayMessage(data.reply_message)}}</p>

                    <div class="{{if isReceiver}} conversation__receiver-reply-text {{else}} conversation-reply-text {{/if}}">
                        <span class="reply-confirm-text fw-normal">{{:~displayMessage(data)}}</span>
                    </div>
                {{else}}
                    <div class="chat-conversation__bubble-text message">
                        {{:~displayMessage(data)}}
                    </div>
                {{/if}}
                <div class="chat-container__time text-nowrap chat-time" style="{{if checkShowNameChat == 1}} left:-40px; {{/if}}"> {{if isReceiver}}
                    {{if checkShowNameChat == 1}}
                        <span class="me-1">{{:data.sender.name}}</span>
                    {{/if}}
                {{/if}}{{:~getChatMagTimeInConversation(data.created_at)}}</div>
                <div class="chat-container__read-status position-absolute {{if isReceiver}} d-none {{else}} {{:readUnread}} {{/if}}">
                    <i class="fa fa-check" aria-hidden="true"></i>
                </div>
            </div>
         {{if isReceiver}}
         <div class="chat-conversation__menu d-flex align-items-center">
            <div class="dropdown btn-group hide-ele msg-options">
                <i class="fa fa-ellipsis-v " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </i>
                <div class="dropdown-menu">
                    <a class="dropdown-item msg-replay" href="#" data-self-reply="0" data-message-id="{{:data.id}}" data-message='{{:data.message}}' data-sender="{{:data.sender.name}}" data-user-id="{{:data.sender.id}}" data-message-type='{{:data.message_type}}'>Reply</a>
                </div>
            </div>
        </div>
        {{/if}}
        </div>
</script>

<script id="groupMsgReadUnreadInfo" type="text/x-jsrender">
    <div class="chat__person-box" data-id="{{:user.user_id}}" data-is_group="0" id="user-{{:user.user_id}}">
        <div class="position-relative chat__person-box-status-wrapper">
            <div class="chat__person-box-avtar">
                <img src="{{:user.photo_url}}" alt="person image" class="user-avatar-img">
            </div>
        </div>
        <div class="chat__person-box-detail">
            <h5 class="mb-1 chat__person-box-name contact-name">{{:user.name}}</h5>
            <p class="mb-0 chat-message">
                {{if ~checkReadAtDate(read_at) }}
                    <span><i class="fa fa-check mx-0 chat__person-box--read" aria-hidden="true"></i>&nbsp;<span class="read_at">{{:~getCalenderFormatForLastSeen(read_at)}}</span></span>
                {{/if}}
            </p>
        </div>
    </div>

</script>
<script id="groupMsgReadUnreadMessage" type="text/x-jsrender">
<div class="chat-conversation__sender message-{{:data.id}} mt-0" data-message_id="{{:data.id}}">
    <div class="chat-conversation__bubble clearfix {{if data.message_type !== 0}} bubble-border-green {{/if}}" >
        {{if data.reply_to }}
            <span class="person-name-texture>{{if loggedInUserId == data.reply_message.sender.id}} You {{else}} {{:data.reply_message.sender.name}} {{/if}}</span>
            <p class="m-1 px-2">{{:~displayMessage(data.reply_message)}}</p>

            <div class="conversation-reply-text">
                <span class="reply-confirm-text fw-normal">{{:data.message}}</span>
            </div>
        {{else}}
            <div class="chat-conversation__bubble-text message">
                    {{:~displayMessage(data)}}
            </div>
        {{/if}}
        <div class="chat-container__time text-nowrap chat-time">{{:~getChatMagTimeInConversation(data.created_at)}}</div>
        <div class="chat-container__read-status position-absolute>
            <i class="fa fa-check" aria-hidden="true"></i>
        </div>
    </div>
</div>
</script>
<script id="singleMessageReadInfoTmpl" type="text/x-jsrender">
    <h6 class="msg-info__column-title"><span class="group-users-count"></span>&nbsp;Read</h6>
    <div class="ps-1" id="msg-read-at-{{:id}}">{{if status}}{{:~getCalenderFormatForLastSeen(updated_at)}} {{else}} - {{/if}}</div>
    <div class="msg-info__divider my-2" id="single-msg-divider"></div>
    <h6 class="msg-info__column-title"><span class="group-users-count"></span>&nbsp;Delivered</h6>
    <div class="ps-1">{{:~getCalenderFormatForLastSeen(created_at)}}</div>
</script>
