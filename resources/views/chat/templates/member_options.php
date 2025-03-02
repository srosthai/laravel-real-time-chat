<script id="tmplMemberOptions" type="text/x-jsrender">
<div class="chat__person-box-msg-time">
    <div class="chat__person-box-group" data-member-id="{{:member_id}}" data-group-id="{{:group_id}}">
        <div class="btn-group">
            <i class="fa fa-ellipsis-v group-details-bar" data-bs-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false">
            </i>
            <div class="dropdown-menu member-options-{{:member_id}}">
                <a class="dropdown-item remove-member-from-group" href="#" data-member-id="{{:member_id}}"
                   data-group-id="{{:group_id}}" id="removeMember-{{:member_id}}">Remove Member</a>
                <a class="dropdown-item make-member-to-group-admin" href="#" data-member-id="{{:member_id}}"
                   data-group-id="{{:group_id}}" id="makeAdmin-{{:member_id}}">Make Admin</a>
            </div>
        </div>
    </div>
</div>


</script>
