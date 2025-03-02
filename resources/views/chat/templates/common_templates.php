<script id="tmplChatSendArea" type="text/x-jsrender">
<div class="chat__area-text">
    <div class="w-100 flex-1 chat__area-form ">
        <input type="text" id="textMessage" class="txtMessage" placeholder="<?php echo  __('messages.placeholder.type_msg') ?>">
    </div>
    <div class="flex-1 d-flex chat__area-btn-group">
        <button class="chat__area-media-btn me-2 btn"
                data-bs-target="#fileUpload"
                id="chat-media-paperclip"
                data-bs-toggle="modal">
            <i class="fa fa-paperclip" aria-hidden="true"></i>
        </button>
        <button type="button" id="btnSend" class="btn chat__area-send-btn chat__area-send-btn--disable">
            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
        </button>
    </div>
</div>

</script>

<script id="tmplUserNewStatus" type="text/x-jsrender">
<i class="nav-icon user-status-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{:status}}" data-original-title="{{:status}}">
    {{:emoji}}
</i>

</script>

<script id="tmplNewMsgIndicator" type="text/x-jsrender">
<div class="chat__msg-day-divider position-relative">
    <span class="chat__msg-day-new-msg position-absolute">new messages</span>
</div>

</script>

<script id="tmplBlockMsgText" type="text/x-jsrender">
<div class="d-flex justify-content-center blocked-message-text">
    <span class="chat__msg-day-title">You have blocked this user.</span>
</div>
</script>

<script id="tmplBlockByMsgText" type="text/x-jsrender">
<div class="d-flex justify-content-center blocked-message-text">
    <span class="chat__msg-day-title">You are blocked by this user.</span>
</div>
</script>

<script id="tmplHiddenTxtMsg" type="text/x-jsrender">
<input type="hidden" id="textMessage" class="hdn-text-message d-none">
</script>

<script id="tmplCloseGroupIcon" type="text/x-jsrender">
<i class="fa fa-lock closed-group-badge" data-bs-toggle="tooltip" data-bs-placement="top" title="The admin only can send messages into the group."></i>

</script>

<script id="tmplPrivateGroupIcon" type="text/x-jsrender">
<i class="fa fa-shield private-group-badge" data-bs-toggle="tooltip" data-bs-placement="top" title="The admin only can add or remove members from the group."> </i>

</script>

<script id="tmplReplayBox" type="text/x-jsrender">
<div class="chat__text-preview" data-message-id="{{:id}}" data-message-type="{{:messageType}}">
    <div class="row d-flex justify-content-md-center">
        <div class="flex-1 chat__reply-text ms-4">
            <h5 class="fw-bolder reply-to-user mb-1">{{:receiver}}</h5>
            {{if messageType !== 0}}
            <div class="replay-div mb-1">
                 {{if messageType == 1 }}
                    <img src="{{:message}}">
                {{/if}}
                {{if messageType == 2 ||  messageType == 3 || messageType == 7 || messageType == 8 || messageType == 4 || messageType == 5 || messageType == 6 }}
                    <img src="{{:~getURLFromMessageType(messageType)}}">
                    <span></span>
                {{/if}}
            </div>
            {{else}}
                <h6 class="replay-message">{{:message}}</h6>
            {{/if}}
        </div>
        <div class="ps-0 mx-3 chat__reply-close cursor-pointer"><i class="fa fa-close text-dark replay-close-btn"></i></div>
    </div>
</div>



</script>

<script id="tmplToday" type="text/x-jsrender">
<div class="chat__msg-day-divider d-flex justify-content-center">
    <span class="chat__msg-day-title">Today</span>
</div>




</script>

<script id="tmplLinkPreview" type="text/x-jsrender">
<div class="">
    {{if message.length}}
        <p class="mb-1 preview-message pb-1">{{:message}}</p>
    {{/if}}
    {{if urlDetails.image}}
        <figure class="figure-img">
        <a href="{{:urlDetails.image}}" data-ybox-group="group2" class="yBox"> <img src="{{:urlDetails.image}}"></a>
<!--            <a href="{{:urlDetails.image}}" data-fancybox="gallery" data-toggle="lightbox" data-gallery="example-gallery" data-src="{{:urlDetails.image}}">-->
<!--                <img src="{{:urlDetails.image}}" class="link-preview-image">-->
<!--            </a>-->
        </figure>
    {{/if}}
    <h4>{{:urlDetails.title}}</h4>
    {{if urlDetails.description}}
        <p>{{:urlDetails.description}}</p>
    {{/if}}
    <p class="mb-0"><a href="{{:urlDetails.url}}" target="_blank">{{:urlDetails.url}}</a></p>
</div>




</script>

<script id="copyPastImgTmplt" type="text/x-jsrender">
    <div class="m-2 copy-img-ele">
        <img src="{{:url}}" class="img-thumbnail">
        <a class="remove-img copy-img-ele__remove-icon">&times;</a>
    </div>


</script>
