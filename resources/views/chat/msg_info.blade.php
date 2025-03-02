<div class="msg-info ms-lg-3">
    <div class="msg-info__header">
        <span class="chat-profile__about">Message info</span>
        <i class="fa fa-times msg-info__close-btn"></i>
    </div>
    <div class="group-profile-data">
        <div class="chat-profile__divider"></div>
        <div id="msg-info-container-msg" class="chat-profile__column">
            <div class="chat-conversation__sender message-88 mt-0" data-message_id="88">
                <div class="chat-conversation__bubble clearfix">
                    <div class="chat-conversation__bubble-text message">
                        {{__('messages.chats.test_message')}}
                    </div>
                    <div class="chat-container__time text-nowrap chat-time">6:37pm</div>
                    <div class="chat-container__read-status position-absolute">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="msg-info__divider" id="read-by-users-divider"></div>
        <div class="msg-info__column" id="read-by-users-section">
            <h6 class="msg-info__column-title">&nbsp;{{__('messages.chats.read_by')}}&nbsp;<span class="group-users-count"></span></h6>
            <p class="msg-info__column-title-detail text-muted mb-0 group-participants">
                <div id="read-by-users"></div>
                <div id="remaining-users" data-remaining_count="0"></div>
            </p>
        </div>
        <div class="msg-info__divider" id="remaining-users-divider"></div>
        <div class="msg-info__column" id="remaining-users-section">
            <h6 class="msg-info__column-title"><span class="group-users-count"></span>&nbsp;{{__('messages.chats.reminder')}}&nbsp;</h6>
            <p class="msg-info__column-title-detail text-muted mb-0 group-participants">
                <div id="remaining-users-list"></div>
            </p>
        </div>
        <div class="msg-info__divider" id="single-msg-divider"></div>
        <div class="msg-info__column" id="single-msg-section">
            <h6 class="msg-info__column-title"><span class="group-users-count"></span>&nbsp;{{__('messages.chats.read')}}
                &nbsp;</h6>
            <div class="ps-1">yesterday at 11:17 PM</div>
            <div class="msg-info__divider my-2" id="single-msg-divider"></div>
            <h6 class="msg-info__column-title"><span class="group-users-count"></span>&nbsp;{{__('messages.chats.delivered')}}
                &nbsp;</h6>
            <div class="ps-1">yesterday at 11:17 PM</div>
        </div>
    </div>
</div>
