<script id="tmplContact" type="text/x-jsrender">
    <div class="contact-area">
        <div class="chat__person-box" data-id="{{:id}}" data-is_group="{{:isGroup}}" id="user-{{:id}}">
            <div class="position-relative chat__person-box-status-wrapper">
                {{if !isGroup}}<div class="chat__person-box-status {{if status}} chat__person-box-status--online {{else !status}} chat__person-box-status--offline{{/if}}"></div>{{/if}}
                <div class="chat__person-box-avtar chat__person-box-avtar--active">
                    <img src="{{:photo_url}}" alt="<?php __('messages.person_image') ?>"
                         class="user-avatar-img">
                </div>
            </div>
            <div class="chat__person-box-detail">
                <h5 class="mb-1 chat__person-box-name contact-name">{{:name}}</h5>
                <p class="mb-0 chat-message">{{if messageInfo !== ''}} {{:~getMessageByItsTypeForChatList(messageInfo.message,
                    messageInfo.message_type, messageInfo.file_name)}} {{/if}}</p>
            </div>
            <div class="chat__person-box-msg-time">
                <div class="chat__person-box-time">{{if messageInfo !== ''}} {{:~getLocalDate(messageInfo.created_at)}} {{/if}}</div>
                <div class="chat__person-box-count {{if messageInfo === ''}} d-none {{/if}}">{{:count}}</div>
                <div class="dropdown msgDropdown">
                    <div class="chat-item-menu action-dropdown text-end pe-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v hide-ele text-logo-color"></i>
                    </div>
                   <div class="dropdown-menu dropdown-menu-right more-btn-conversation-item action-dropdown-menu">
                       <a class="dropdown-item text-center chat__person-box-delete more-delete-option">
                            Delete
                        </a>
                        <a class="dropdown-item text-center chat__person-box-archive">
                            Archive Chat
                        </a>
                   </div>
                </div>
            </div>
        </div>
    </div>


</script>
