<script id="tmplBlockedUsers" type="text/x-jsrender">
    {{for users}}
    <li class="list-group-item blocked-user-list-chat-select__list-item blocked-user-{{:id}} align-items-center d-flex justify-content-between">

        <div class="d-flex">
            <div class="new-conversation-img-status position-relative me-2">
                <div class="new-conversation-img-status__inner">
                    <img src="{{:photo_url}}" alt="user-avatar-img"
                         class="user-avatar-img add-user-img">
                </div>
            </div>
            <span class="add-user-contact-name align-self-center">{{:name}}</span>
        </div>
        <div><button class="btn btn-success btn-unblock" data-id="{{:id}}">Unblock</button></div>
    </li>
    {{/for}}

</script>
