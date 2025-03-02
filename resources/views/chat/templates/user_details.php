<script id="tmplUserDetails" type="text/x-jsrender">
    <div class="chat-profile__header">
        <span class="chat-profile__about"><?php echo trans('messages.about') ?></span>
        <i class="fa fa-times chat-profile__close-btn"></i>
    </div>
    <div class="chat-profile__person chat-profile__person--active mb-2">
        <div class="chat-profile__avatar">
            <img src="{{:photo_url}}" alt="" class="img-fluid user-about-image">
            {{if !is_my_contact && is_private_account }}
                <i class="fa fa-lock private-user-badge"> </i>
            {{/if}}
        </div>
    </div>
    {{if (is_online && !is_blocked) && (is_my_contact || !is_private_account)}}
    <div class="chat-profile__person-status my-3 text-capitalize">
            Online
    </div>
    {{else}}

        {{if (!is_online && !is_blocked) && (is_my_contact || !is_private_account) }}
        <div class="chat-profile__person-last-seen">
            {{if last_seen !== '' && last_seen !== null}}
                last seen at {{:~getCalenderFormatForLastSeen(last_seen)}}
            {{else}}
                last seen at: Never
            {{/if}}
        </div>
        {{/if}}

    {{/if}}

    <div class="user-profile-data">
        <div class="chat-profile__divider"></div>
        <div class="chat-profile__column d-flex">
            <i class="fa fa-user" aria-hidden="true"></i>
            <div class="ms-3">
                <h6 class="chat-profile__column-title mb-0"><?php echo trans('messages.bio') ?></h6>
                <p class="chat-profile__column-title-detail text-muted mb-0 user-about">
                     {{if about}}
                        {{:about}}
                    {{else}}
                        No bio added yet...
                    {{/if}}
                </p>
            </div>
        </div>
        {{if is_my_contact || !is_private_account }}
            <div class="chat-profile__column d-flex">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <div class="ms-3">
                    <h6 class="chat-profile__column-title mb-0"><?php echo trans('messages.phone') ?></h6>
                    <p class="chat-profile__column-title-detail text-muted mb-0 user-phone">
                        {{if phone}}
                            {{:phone}}
                        {{else}}
                            No phone added yet...
                        {{/if}}
                    </p>
                </div>
            </div>
            <div class="chat-profile__column d-flex">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <div class="ms-3 truncate-div">
                    <h6 class="chat-profile__column-title mb-0 text-truncate"><?php echo trans('messages.email') ?></h6>
                    <p class="chat-profile__column-title-detail text-muted mb-0 text-truncate user-email">{{:email}}</p>
                </div>
            </div>
        {{/if}}
    </div>
    <div class="group-profile-data">
        <div class="chat-profile__divider"></div>
        <div class="chat-profile__divider"></div>
    <input type="hidden" id="senderId" value={{:id}}>
    <div class="chat-profile__divider"></div>
    <!-- profile media and mute block section -->
    <div class="chat-profile__column chat-profile__column--media">
        <h6 class="chat-profile__column-title"><?php echo trans('messages.media') ?></h6>
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

    <div class="chat-profile__divider"></div>
    <div class="chat-profile__divider"></div>
    <div class="chat-profile__divider"></div>
    <div class="chat-profile__column">
        <h6 class="chat-profile__column-title"><?php echo trans('messages.common_group') ?>
            <span class="float-end">{{if common_groups.length > 0}} {{:common_groups.length}} {{/if}}</span>
        </h6>
        {{for common_groups}}
            <div class="chat__person-box" data-id="{{:id}}" data-is_group="1">
                <div class="position-relative chat__person-box-status-wrapper">
                    <div class="chat__person-box-avtar chat__person-box-avtar--active">
                        <img src="{{:photo_url}}" alt="person image" class="user-avatar-img">
                    </div>
                </div>
                <div class="chat__person-box-detail">
                    <h5 class="mb-1 chat__person-box-name contact-name">{{:name}}</h5>
                    {{if users.length > 0 ~groupUsersName=~groupUsers(users)}}
                        <p class="mb-0 chat-message" title="{{:~groupUsersName}}">
                            {{:~groupUsersName}}
                        </p>
                    {{/if}}
                </div>
            </div>
        {{/for}}
        {{if common_groups.length === 0}}
            <p class="no-group-members-found text-center">No common group...</p>
        {{/if}}
    </div>

    {{if !is_super_admin}}
    <div class="chat-profile__column">
        {{if is_blocked_by_auth_user}}
        <div class="switch-checkbox chat-profile__switch-checkbox">
            <input type="checkbox" id="switch" class="block-unblock-user-switch" checked/><label for="switch" class="mb-0 me-2">Toggle</label>
            <span class="chat-profile__column-title-detail text-muted mb-0 block-unblock-span">Unblock</span>
        </div>
        {{else}}
              <div class="switch-checkbox chat-profile__switch-checkbox">
            <input type="checkbox" id="switch" class="block-unblock-user-switch"/><label for="switch" class="mb-0 me-2">Toggle</label>
            <span class="chat-profile__column-title-detail text-muted mb-0 block-unblock-span">Block</span>
        </div>
        {{/if}}
    </div>
    <div class="chat-profile__divider"></div>
    <div class="chat-profile__column">
        <button type="button" class='btn btn-danger open-report-user-modal' id="open-report-user-modal" data-id="{{:id}}" {{:~disabledIfReported(reported_user)}}><?php echo trans('messages.report_user') ?></button>
    </div>
    {{/if}}
</div>
</script>
