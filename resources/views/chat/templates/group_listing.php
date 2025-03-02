<script id="tmplGroupListing" type="text/x-jsrender">
{{for data}}
    <li class="list-group-item user-list-chat-select__list-item align-items-center group-list-{{:id}}">
        <input type="hidden" class="add-chat-user-id" value="{{:id}}">
        <div class="new-conversation-img-status position-relative me-2 user-{{:id}}" data-status="0" data-is-group="1">
            <div class="new-conversation-img-status__inner">
                <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
            </div>
        </div>
        <span class="add-user-contact-name">{{:name}}</span>
    </li>
{{/for}}

</script>
