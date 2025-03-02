<script id="tmplConversation" type="text/x-jsrender">
    <div class="chat-header">
        <div class="chat__area-header position-relative">
            <div class="d-flex justify-content-between align-items-center flex-1 mx-3 chat__header-top">
                <input type="hidden" id="toId" value="{{:user.id}}">
                <input type="hidden" id="chatType" value="{{:user.id}}">
                <div class="d-flex align-items-center w-100">
                    <span class="back-to-chat d-flex justify-content-center align-items-center">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </span>
                    <div class="chat__area-header-avatar">
                        <img src="{{:user.photo_url}}" alt="<?php __('messages.person_image') ?>" class="img-fluid chat-header-img">
                    </div>
                    <div class="ps-3 chat__profile-avatar">
                        <h5 class="my-0 chat__area-title contact-title text-truncate">{{>user.name}}
                        <span class="contact-title-status">
                        {{if user.is_my_contact && ~checkUserStatus(user)}}
                            <i class="nav-icon user-status-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{:user.user_status.status}}" data-original-title="{{:user.user_status.status}}">
                                {{:user.user_status.emoji}}
                            </i>
                        {{/if}}
                        </span>
                        </h5>
                        <div class="typing position-relative online-status {{if user.is_blocked || isGroup}} d-none {{/if}}" >
                            {{if user.is_online}} online {{else}} last seen at: <i>{{:lastSeenTime}}</i>{{/if}}
<!--                            <span class="chat__area-header-status"></span>-->
                            <span class="ps-3"><?php __('messages.online') ?></span>
                        </div>
                    </div>
                    <div class="cursor-pointer d-xl-none ms-auto hamburger-top"
                        id="dropdownMenuButton" aria-expanded="false">
                         {{if my_role === 2}}
                        <a href="javascript:void(0)" class="text-decoration-none me-2 btn-add-members" data-group-id="{{:user.id}}" title="<?php __('messages.add_members') ?>"><img src="images/icons/mobile-add.png" alt="add"></a>
                        {{/if}}
                        <i class="fa fa-bars open-profile-menu" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="chat__area-action">
                    <!-- setting view -->
                    {{if my_role === 2}}
                    <a href="javascript:void(0)" class="text-decoration-none me-2 btn-add-members" data-group-id="{{:user.id}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="<?php echo trans('messages.add_members') ?>
    " title="<?php echo trans('messages.add_members') ?>"><img src="images/icons/add.png" class="add-icon" alt="add"></a>
                    {{/if}}
                    <div class="chat__area-icon open-profile-menu ms-2">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading-message chat__area-header-loader d-none">
            <svg width="150px" height="75px" viewBox="0 0 187.3 93.7"
            preserveAspectRatio="xMidYMid meet">
            <path stroke="#00c6ff" id="outline" fill="none" stroke-width="5" stroke-linecap="round"
            stroke-linejoin="round" stroke-miterlimit="10"
            d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"/>
            <path id="outline-bg" opacity="0.05" fill="none" stroke="#f5981c" stroke-width="5"
            stroke-linecap="round"
            stroke-linejoin="round" stroke-miterlimit="10"
            d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"/>
            </svg>
        </div>
        <div class="chat-conversation" id="conversation-container"></div>
    </div>



</script>
