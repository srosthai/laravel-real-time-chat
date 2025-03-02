<script id="tmplMyContactsUsersList" type="text/x-jsrender">
{{for users}}
<li class="list-group-item user-list-chat-select__list-item align-items-center chat-user-{{:id}} {{:~getGender(gender)}} {{:~getOnOffClass(is_online)}}">
    <input type="hidden" class="add-chat-user-id" value="{{:id}}">
        <div class="new-conversation-img-status position-relative me-2 user-{{:id}}" data-status="{{:is_online}}">
            <div class="chat__person-box-status {{if is_online}} chat__person-box-status--online {{else}} chat__person-box-status--offline {{/if}}"></div>
            <div class="new-conversation-img-status__inner">
                <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
            </div>
        </div>
    <span class="add-user-contact-name">{^{>name}}
        <span class="my-contact-user-status">
            {{if ~checkUserStatusForGroupMember(user_status) && ~isInBlockedList(id)}}
                <i class="nav-icon user-status-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{:user_status.status}}" data-original-title="{{:user_status.status}}">
                    {{:user_status.emoji}}
                </i>
            {{/if}}
        </span>
    </span>
</li>
{{/for}}


</script>
