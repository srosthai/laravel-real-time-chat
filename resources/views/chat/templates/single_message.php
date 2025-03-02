<script id="tmplSingleMessage" type="text/x-jsrender">
<div  id="send-receive-direction" class="chat-conversation__sender" data-message_id="{{:randomMsgId}}">
    <div class="chat-conversation__avatar">
            {{if senderRole == 'Admin'}}
            <span class="highlighter" data-bs-toggle="tooltip" title="Admin"><img src="./assets/images/crown.svg"></span>
            {{/if}}
        {{if is_group}}
        <img src="{{:senderImg}}" alt="" class="img-fluid conversation-user-img"
             data-bs-toggle="tooltip" data-bs-placement="top" title="{{:senderName}}">
        {{else}}
            <img src="{{:senderImg}}" alt="" class="img-fluid conversation-user-img">
        {{/if}}
    </div>
    <div class="chat-conversation__menu d-flex align-items-center">
           <div class="dropdown btn-group hide-ele msg-options">
                <i class="fa fa-ellipsis-v " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </i>
                <div class="dropdown-menu">
                    <a class="dropdown-item msg-delete-icon" href="#">Delete Message</a>
                    <a class="dropdown-item msg-delete-for-everyone" href="#">Delete For Everyone</a>
                    <a class="dropdown-item msg-replay" href="#" data-self-reply="1" data-message-id="{{:randomMsgId}}"  data-message='{{:message}}' data-message-type='{{:randomMsgId}}'>Reply</a>
                     <a class="dropdown-item open-msg-info" data-message-id="{{:randomMsgId}}" data-is_group="{{:is_group}}">
                        Info
                     </a>
                </div>
            </div>
    </div>
    <div class="chat-conversation__bubble clearfix">
        {{if replyMessage.length}}
            <span class="person-name-texture">{{:receiverName}}</span>
                <p class="m-1 px-2">{{:replyMessage}}</p>
                <div class="conversation-reply-text">
                <span class="reply-confirm-text fw-normal">{{:message}}</span>
            </div>
               <div class="chat-container__time text-nowrap chat-time">{{:time}}</div>
            <div class="chat-container__read-status position-absolute">
                <i class="fa fa-check" aria-hidden="true"></i>
            </div>
        {{else}}
            <div class="chat-conversation__bubble-text message">
                {{:message}}
            </div>
            <div class="chat-container__time text-nowrap chat-time">{{:time}}</div>
            <div class="chat-container__read-status position-absolute">
                <i class="fa fa-check" aria-hidden="true"></i>
            </div>
        {{/if}}
    </div>

</div>


</script>
