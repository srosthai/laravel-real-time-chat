<script id="tmplAddChatUsersList" type="text/x-jsrender">
{{for users}}
<li class="list-group-item user-list-chat-select__list-item align-items-center chat-user-{{:id}} {{:~getGender(gender)}}" data-status="{{:is_online}}">
    <input type="hidden" class="add-chat-user-id" value="{{:id}}">
        <div class="new-conversation-img-status position-relative me-2 user-{{:id}}" data-status="{{:is_online}}">
        <div class="chat__person-box-status {{if is_online}} chat__person-box-status--online {{else}} chat__person-box-status--offline {{/if}}"></div>
        <div class="new-conversation-img-status__inner">
            <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
        </div>
        </div>
    <span class="add-user-contact-name">{^{>name}}</span>
</li>
{{/for}}


</script>
