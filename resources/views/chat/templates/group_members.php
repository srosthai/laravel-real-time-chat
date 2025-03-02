<!--this template (tmplAddGroupMembers) not used anymore-->
<script id="tmplAddGroupMembers" type="text/x-jsrender">
{{for users}}
    {{if ~isMembersInGroup(~root.group_members, id)}}
    <li class="list-group-item group-members-list-chat-select__list-item  align-items-center d-flex justify-content-between opacity-07" id="groupMember-{{:id}}">
        <div class="d-flex">
        <input type="hidden" class="add-chat-user-id" value="{{:id}}">
        <div class="new-conversation-img-status position-relative me-2 user-{{:id}}" data-status="0" data-is-group="1">
            <div class="new-conversation-img-status__inner">
                <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
            </div>
        </div>
        <span class="add-user-contact-name align-self-center">{{:name}}</span>
        </div>
        <div><input name="group_members" type="checkbox" class="select-group-members" value="{{:id}}" checked disabled></div>
    </li>
    {{else}}
    <li class="list-group-item group-members-list-chat-select__list-item align-items-center d-flex justify-content-between" id="groupMember-{{:id}}">
        <div class="d-flex">
        <input type="hidden" class="add-chat-user-id" value="{{:id}}">
        <div class="new-conversation-img-status position-relative me-2 user-{{:id}}" data-status="0" data-is-group="1">
            <div class="new-conversation-img-status__inner">
                <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
            </div>
        </div>
        <span class="add-user-contact-name align-self-center">{{:name}}</span>
        </div>
        <div><input name="group_members" type="checkbox" class="select-group-members" value="{{:id}}"></div>
    </li>
    {{/if}}
{{/for}}


</script>

<script id="tmplAddedGroupMembers" type="text/x-jsrender">
<!--    <li class="list-group-item group-members-list-chat-select__list-item align-items-center d-flex justify-content-between opacity-07 not-allowed">-->
<!--        <div class="d-flex w-100 align-items-center">-->
<!--            <input type="hidden" class="add-group-user-id" value="{{:id}}">-->
<!--            <div class="new-conversation-img-status position-relative me-2 user-{{:id}}">-->
<!--                <div class="new-conversation-img-status__inner">-->
<!--                    <img src="{{:photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="truncate-button">-->
<!--                <div class="add-user-contact-name align-self-center text-truncate">{{:name}}</div>-->
<!--                <div class="add-user-contact-name align-self-center text-truncate">{{:email}}</div>-->
<!--            </div>-->
<!--            <button class="btn btn-sm float-end btn-danger remove-group-member disabled ms-auto" data-id="{{:id}}" disabled="disabled">Remove</button>-->
<!--        </div>-->
<!--    </li>-->



</script>

<script id="tmplNoGroupMembers" type="text/x-jsrender">
<div class="text-center no-member-added h-130">
    <div class="chat__not-selected">
        <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
        </div>
        <span><?php echo trans('messages.no_member_added_yet') ?></span>
    </div>
</div>

</script>
