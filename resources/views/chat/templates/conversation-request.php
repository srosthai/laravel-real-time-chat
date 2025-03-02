<script id="getChatRequestTmpl" type="text/x-jsrender">
<div class="request__wrapper">
    <div class="request__header">
        <div class="profile-image">
            <img src="{{:photo_url}}">
        </div>
    </div>
    {{if ~checkReqAlreadyDeclined(chat_req)}}
        <div class="request__content">
            <h3 class="request__content-title">You have declined chat request of this user.</h3>
        </div>
    {{else}}
        <div class="request__content">
            <h3 class="request__content-title">Your private conversation with {{:name}}.</h3>
            <span class="text-muted mt-3">{{:name}} wants to chat with you.</span>
        </div>
        <div class="request__message">
            <h5 class="text-muted">Join private conversation on this device?</h5>
            <div class="request__buttons">
                <a class="decline-btn" id="declineChatReq" data-id={{:chat_req_id}}>Decline</a>
                <a class="accept-btn" id="acceptChatReq" data-id={{:chat_req_id}}>Accept</a>
            </div>
        </div>
    {{/if}}
</div>

</script>

<script id="sendRequestTmpl" type="text/x-jsrender">
<div class="request__wrapper">
    <div class="request__header">
        <div class="profile-image">
            <img src="{{:photo_url}}">
        </div>
    </div>
    {{if ~checkReqAlreadySent(chat_req)}}
        <div class="request__content">
            <h3 class="request__content-title">You have send request to this user.</h3>
        </div>
    {{else}}
        <div class="request__content">
            <h3 class="request__content-title">Your private conversation with {{:name}}.</h3>
        </div>
        <div class="send__request__message text-center">
            <h5 class="text-muted">Start conversation with {{:name}} now</h5>
            <input type="text" placeholder="Message..." id="chatRequestMessage-{{:id}}">
            <div class="mt-5 text-center">
                <a id="sendChatRequest" class="send-request-btn" data-id={{:id}}>Send Chat Request</a>
            </div>
        </div>
    {{/if}}
</div>

</script>
