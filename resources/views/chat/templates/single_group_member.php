<script id="tmplSingleGroupMember" type="text/x-jsrender">
{{for users}}
<div class="chat__person-box group-member-{{:id}}" data-id="{{:id}}" data-is_group="0" id="user-{{:id}}">
        <div class="position-relative chat__person-box-status-wrapper">
            <div class="chat__person-box-avtar chat__person-box-avtar--active">
                <img src="{{:photo_url}}" alt="person image" class="user-avatar-img">
            </div>
        </div>
        <div class="chat__person-box-detail">
            <h5 class="mb-1 chat__person-box-name contact-name">{{:name}}</h5>
        </div>
         {{if ~root.my_role === 2}}
          <div class="chat__person-box-msg-time">
            <div class="chat__person-box-group" data-member-id="{{:id}}" data-group-id="{{:~root.id}}">
               <div class="btn-group">
                  <i class="fa fa-ellipsis-v group-details-bar" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  </i>
                  <div class="dropdown-menu member-options-{{:id}}">
                    <a class="dropdown-item remove-member-from-group" href="#" data-member-id="{{:id}}" data-group-id="{{:~root.id}}" id="removeMember-{{:id}}">Remove Member</a>
                    <a class="dropdown-item make-member-to-group-admin" href="#" data-member-id="{{:id}}" data-group-id="{{:~root.id}}" id="makeAdmin-{{:id}}">Make Admin</a>
                  </div>
               </div>
            </div>
            </div>
        {{/if}}
    </div>
{{/for}}

</script>
