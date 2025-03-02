<script id="tmplGroupDetails" type="text/x-jsrender">
    <div class="chat-profile__header">
        <span class="chat-profile__about"><?php echo trans('messages.about') ?></span>
        <div>
        {{if !removed_from_group && my_role === 2}}
            <a href="javascript:void(0)" class="text-decoration-none edit-group text-center me-2" data-id="{{:id}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="<?php echo trans('messages.edit_group') ?>"><img src="images/icons/mobile-edit.png" alt="edit"></a>
        {{/if}}
            <i class="fa fa-times chat-profile__close-btn"></i>
        </div>
    </div>
    <div class="chat-profile__person--active mb-2 profile__inner m-auto">
        <div class="chat-profile__avatar text-center chat-profile__img-wrapper group-profile-image">
         {{if group_type === 2}}
          <i class="fa fa-lock closed-group-badge" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="The admin only can send messages into the group."> </i>
          {{/if}}
          {{if privacy === 2}}
          <i class="fa fa-shield private-group-badge" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="The admin only can add or remove members from the group."> </i>
        {{/if}}
            <img src="{{:photo_url}}" alt="" class="img-fluid user-about-image img-circle" id="groupDetailsImage-{{:id}}">
        </div>
    </div>
    <div class="chat-profile__person-last-seen chat-profile__column mb-0">
     <div class="divGroupDetails d-flex justify-content-around row">
            <div class="col-12">
            <h4 id="groupDetailsName-{{:id}}" class="align-items-center mb-0">{{:name}}</h4>
            </div>
            <span class="mt-2">Created By {{:group_created_by}}, {{:~getLocalDate(created_at, 'DD/MM/YYYY')}} </span>
    </div>
    </div>

    <div class="group-profile-data group-about-sidebar">
        <div class="chat-profile__divider"></div>
        <div class="chat-profile__column">
            <h6 class="chat-profile__column-title"><?php echo trans('messages.group.description') ?></h6>
            <p class="chat-profile__column-title-detail text-muted mb-0 group-desc" id="groupDetailsDescription-{{:id}}">
                {{if description}}
                    {{:description}}
                {{else}}
                    No description added yet...
                {{/if}}
            </p>
        </div>
        <div class="chat-profile__divider"></div>
            <nav class="nav nav-pills m-3" id="myTab" role="tablist">
                <a class="nav-item nav-link active group-members-tab" id="nav-group-members-tab" data-bs-toggle="tab" href="#nav-group-members"
                   role="tab" aria-controls="nav-group-members " aria-expanded="true"> <i
                            class="ti-user"></i><?php echo trans('messages.participants') ?><span class="badge badge-pill badge-secondary ms-2 members-count" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="Total group members">{{:members_count}}</span></a>
                <a class="nav-item nav-link" id="nav-group-medias-tab" data-bs-toggle="tab" href="#nav-group-medias"
                   role="tab" aria-controls="nav-group-medias"><?php echo trans('messages.media') ?></a>
            </nav>
            <div class="tab-content white-border" id="nav-tabContent">
                <div class="tab-pane fade show active div-group-members-nav" id="nav-group-members" role="tabpanel"
                                 aria-labelledby="nav-group-members-tab">
                    <p class="chat-profile__column-title-detail text-muted mb-0 group-participants"></p>
                    {{for users}}
                    <div class="chat__person-box group-member-{{:id}} {{if ~root.logged_in_user_id === id}} non-clickable {{/if}}" data-id="{{:id}}" data-is_group="0" id="user-{{:id}}">
                        <div class="position-relative chat__person-box-status-wrapper">
                            <div class="chat__person-box-avtar chat__person-box-avtar--active">
                                <img src="{{:photo_url}}" alt="person image" class="user-avatar-img">
                            </div>
                        </div>
                        <div class="chat__person-box-detail">
                            <h5 class="mb-1 chat__person-box-name contact-name">{{:name}}
                            <span class="group-user-status">
                                {{if ~checkUserStatusForGroupMember(user_status)}}
                                    <i class="nav-icon user-status-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{:user_status.status}}" data-original-title="{{:user_status.status}}">
                                        {{:user_status.emoji}}
                                    </i>
                                {{/if}}
                            </span>
                            </h5>
                             {{if pivot.role === 2}}
                                <span class="badge badge-pill badge-primary">{{if ~root.created_by === id}} Owner {{else}} Admin{{/if}}</span>
                            {{/if}}
                        </div>
                         {{if ~root.created_by !== id && ~root.my_role === 2 && ~root.logged_in_user_id != id && !~root.removed_from_group}}
                            <div class="chat__person-box-msg-time">
                                <div class="chat__person-box-group" data-member-id="{{:id}}" data-group-id="{{:~root.id}}">
                                   <div class="btn-group">
                                      <i class="fa fa-ellipsis-v group-details-bar" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      </i>
                                      <div class="dropdown-menu member-options-{{:id}}">
                                        <a class="dropdown-item remove-member-from-group" href="#" data-member-id="{{:id}}" data-group-id="{{:~root.id}}" id="removeMember-{{:id}}">Remove Member</a>
                                       {{if pivot.role === 2}}
                                        <a class="dropdown-item dismiss-admin-member" href="#" data-member-id="{{:id}}" data-group-id="{{:~root.id}}" id="dismissAdmin-{{:id}}">Dismiss As Admin</a>
                                        {{else}}
                                             <a class="dropdown-item make-member-to-group-admin" href="#" data-member-id="{{:id}}" data-group-id="{{:~root.id}}" id="makeAdmin-{{:id}}">Make Admin</a>
                                        {{/if}}
                                      </div>
                                    </div>
                                </div>
                            </div>
                        {{/if}}
                    </div>
                    {{/for}}
                    {{if users.length === 0}}
                        <p class="no-group-members-found text-center">No group members found...</p>
                    {{/if}}
                </div>
                <div class="tab-pane fade show" id="nav-group-medias" role="tabpanel"
                         aria-labelledby="nav-group-medias-tab">
                    <div class="chat-profile__column--media">

                        <div class="chat-profile__media-container">
                            {{if media && media.length}}
                                {{for media}}
                                    {{:~prepareMedia(#data)}}
                                {{/for}}
                            {{else}}
                                <span class="no-photo-found text-muted">No media shared yet...</span>
                            {{/if}}
                        </div>
                    </div>
                </div>
            </div>

    <div class="chat-profile__divider"></div>
    {{if privacy === 2 && my_role === 2 && !removed_from_group}}
    <div class="chat-profile__column pb-0">
        <a href="#" class='btn btn-primary btn-add-members' data-group-id="{{:id}}"><?php echo trans('messages.chats.add_members') ?></a>
    </div>
    {{else privacy === 1 && !removed_from_group}}
    <div class="chat-profile__column pb-0">
        <a href="#" class='btn btn-primary btn-add-members' data-group-id="{{:id}}"><?php echo trans('messages.chats.add_members') ?></a>
   </div>
    {{/if}}
    {{if !group_deleted_by_owner && removed_from_group || (created_by === logged_in_user_id)}}
    <div class="chat-profile__column pt-1">
       <a href="#" class='btn btn-danger btn-delete-group' data-group-id="{{:id}}"><?php echo trans('messages.group.delete_group') ?></a>
    </div>
    {{else !removed_from_group}}
    <div class="chat-profile__column pt-1">
       <a href="#" class='btn btn-danger btn-leave-from-group' data-group-id="{{:id}}"><?php echo trans('messages.group.leave_group') ?></a>
    </div>
    {{/if}}
</script>
