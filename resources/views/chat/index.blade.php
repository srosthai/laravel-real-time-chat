@extends('layouts.app')
@section('title')
    {{ __('messages.conversations') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/yBox.min.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/video-js.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/new-conversation.css') }}">
@endsection
@section('content')
    <div class="page-container">
        <div class="chat-container chat">
            <div class="chat__inner">
                <!-- left section of chat area (chat person selection area) -->
                <div class="chat__people-wrapper chat__people-wrapper--responsive">
                    <div class="chat__people-wrapper-header">
                        <span class="h3 mb-0">{{ __('messages.conversations') }}</span>
                        <div class="d-flex chat__people-wrapper-btn-group ms-1">
                            <i class="nav-icon fa fa-bars align-top chat__people-wrapper-bar"></i>
                            @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
                                @if ($enableGroupSetting == 1)
                                    @if (Auth::user()->hasRole('Admin'))
                                        <div class="chat__people-wrapper-button btn-create-group me-2 d-flex align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#createNewGroup">
                                            <i class="nav-icon group-icon color-green remove-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="{{ __('messages.create_new_group') }}"><img
                                                    src="{{ asset('assets/icons/group.png') }}" width="33"
                                                    height="33"></i>
                                        </div>
                                    @elseif($membersCanAddGroup == 1)
                                        <div class="chat__people-wrapper-button btn-create-group me-2 d-flex align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#createNewGroup">
                                            <i class="nav-icon group-icon color-green remove-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="{{ __('messages.create_new_group') }}"><img
                                                    src="{{ asset('assets/icons/group.png') }}" width="33"
                                                    height="33"></i>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <div class="chat__people-wrapper-button d-flex align-items-center" data-bs-toggle="modal"
                                data-bs-target="#addNewChat">
                                <i class="nav-icon remove-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="{{ __('messages.new_conversation') }}"><img
                                        src="{{ asset('assets/icons/bubble-chat.png') }}" width="30"
                                        height="30"></i>
                            </div>
                            <i
                                class="nav-icon fa fa-times align-top chat__people-close-bar d-sm-none d-block align-self-center ms-2"></i>
                        </div>
                    </div>
                    <div class="chat__search-wrapper">
                        <div class="chat__search clearfix chat__search--responsive">
                            <i class="fa fa-search"></i>
                            <input type="search" placeholder="{{ __('messages.search') }}" class="chat__search-input"
                                id="searchUserInput">
                            <i class="fa fa-search d-lg-none chat__search-responsive-icon"></i>
                        </div>
                    </div>
                    <ul class="nav nav-tabs chat__tab-nav mb-1 border-bottom-0" id="chatTabs">
                        <li class="nav-item">
                            <a data-bs-toggle="tab" id="activeChatTab" class="nav-link active login-group__sub-title"
                                href="#chatPeopleBody">{{ __('messages.chats.active_chat') }}</a>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="tab" id="archiveChatTab" class="nav-link login-group__sub-title"
                                href="#archivePeopleBody">{{ __('messages.chats.archive_chat') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content chat__tab-content">
                        <div class="chat__people-body tab-pane fade in active show" id="chatPeopleBody">
                            <div id="infyLoader" class="infy-loader chat__people-body-loader">
                                @include('partials.infy-loader')
                            </div>
                            <div class="text-center no-conversation" style="display: none">
                                <div class="chat__no-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-commenting-o" aria-hidden="true"></i>
                                    </div>
                                    {{ __('messages.no_conversation_found') }}
                                </div>
                            </div>
                            <div class="text-center no-conversation-yet" style="display: none">
                                <div class="chat__no-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-commenting-o" aria-hidden="true"></i>
                                    </div>
                                    {{ __('messages.no_conversation_added_yet') }}
                                </div>
                            </div>
                            <div id="loadMoreConversationBtn" style="display: none">
                                <a href="javascript:void(0)" class="load-more-conversation">Load More</a>
                            </div>
                        </div>
                        <div class="chat__people-body tab-pane fade in active" id="archivePeopleBody">
                            <div class="text-center no-archive-conversation">
                                <div class="chat__no-archive-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-commenting-o" aria-hidden="true"></i>
                                    </div>
                                    {{ __('messages.no_conversation_found') }}
                                </div>
                            </div>
                            <div class="text-center no-archive-conversation-yet">
                                <div class="chat__no-archive-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-commenting-o" aria-hidden="true"></i>
                                    </div>
                                    {{ __('messages.no_conversation_added_yet') }}
                                </div>
                            </div>
                            <div id="loadMoreArchiverConversationBtn" style="display: none">
                                <a href="javascript:void(0)"
                                    class="load-more-archive-conversation">{{ __('messages.chats.load_more') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ left section of chat area -->
                <!-- right section of chat area (chat conversation area)-->
                <div class="chat__area-wrapper ms-lg-3">
                    @include('chat.no-chat')
                </div>
                <!--/ right section of chat area-->
                <!-- profile section (chat profile section)-->
                @include('chat.chat_profile')
                @include('chat.msg_info')
                <!--/ profile section -->
            </div>
        </div>
        <!-- Modal -->
        <div id="addNewChat" class="modal fade" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered conversation-modal">
                <!-- Modal content-->
                <div class="modal-content modal-new-conversation">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">
                            <i class="ti-user"></i>{{ __('messages.group.new_conversations') }} @if ($enableGroupSetting == 1)
                                / {{ __('messages.group.groups') }}
                            @endif
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <nav class="nav nav-pills flex-wrap" id="myTab" role="tablist">
                            @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
                                <a class="nav-item nav-link active" id="nav-my-contacts-tab" data-bs-toggle="tab"
                                    href="#nav-my-contacts" role="tab" aria-controls="nav-my-contacts-tab"
                                    aria-expanded="true"> <i class="ti-user"></i>{{ __('messages.my_contacts') }}
                                </a>
                            @endif
                            <a class="nav-item nav-link wrap-text" id="nav-users-tab" data-bs-toggle="tab"
                                href="#nav-users" role="tab" aria-controls="nav-users" aria-expanded="true">
                                <i class="ti-user"></i>{{ __('messages.new_conversation') }}
                            </a>
                            @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
                                @if ($enableGroupSetting == 1)
                                    <a class="nav-item nav-link" id="nav-groups-tab" data-bs-toggle="tab"
                                        href="#nav-groups" role="tab"
                                        aria-controls="nav-groups">{{ __('messages.group.groups') }}</a>
                                @endif
                            @endif
                            <a class="nav-item nav-link" id="nav-blocked-users-tab" data-bs-toggle="tab"
                                href="#nav-blocked-users" role="tab"
                                aria-controls="nav-blocked-users">{{ __('messages.blocked_users') }}</a>
                        </nav>

                        <div class="tab-content search-any-member mt-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-my-contacts" role="tabpanel"
                                aria-labelledby="nav-my-contacts-tab">
                                @livewire('my-contacts-search', ['myContactIds' => $myContactIds, 'blockUserIds' => $blockUserIds])
                            </div>
                            <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
                                @livewire('search-users', ['myContactIds' => $myContactIds, 'blockUserIds' => $blockUserIds])
                            </div>
                            @if ($enableGroupSetting == 1)
                                <div class="tab-pane fade" id="nav-groups" role="tabpanel"
                                    aria-labelledby="nav-groups-tab">
                                    @livewire('group-search')
                                </div>
                            @endif
                            <div class="tab-pane fade show" id="nav-blocked-users" role="tabpanel"
                                aria-labelledby="nav-blocked-users-tab">
                                @livewire('blocked-user-search', ['blockedByMeUserIds' => $blockedByMeUserIds])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('chat.group_modals')
        @include('chat.edit_group_modals')
        @include('chat.report_user_modal')
    </div>
    @include('chat.templates.conversation-template')
    @include('chat.templates.message')
    @include('chat.templates.no-messages-yet')
    @include('chat.templates.no-conversation')
    @include('chat.templates.group_details')
    @include('chat.templates.user_details')
    @include('chat.templates.group_listing')
    @include('chat.templates.group_members')
    @include('chat.templates.single_group_member')
    @include('chat.group_members_modal')
    @include('chat.templates.blocked_users_list')
    @include('chat.templates.add_chat_users_list')
    @include('chat.templates.badge_message_template')
    @include('chat.templates.member_options')
    @include('chat.templates.single_message')
    @include('chat.templates.contact_template')
    @include('chat.templates.conversations_list')
    @include('chat.templates.common_templates')
    @include('chat.templates.my_contacts_listing')
    @include('chat.templates.conversation-request')
    @include('chat.copyImageModal')
@endsection
@section('page_js')
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/directive.min.js') }}"></script>
    <script src="{{ asset('js/yBox.min.js') }}"></script>
    <script src="{{ mix('assets/js/video.min.js') }}"></script>
@endsection
@section('scripts')
    <!--custom js-->
    <script>
        let userURL = '{{ url('users') }}/'
        let userListURL = '{{ url('users-list') }}' // not use in anywhere
        let chatSelected = false
        let csrfToken = '{{ csrf_token() }}'
        let authUserName = '{{ getLoggedInUser()->name }}'
        let authImgURL = '{{ getLoggedInUser()->photo_url }}'
        let deleteConversationUrl = '{{ url('conversations') }}/'
        let getUsers = '{{ url('get-users') }}' //not used in anywhere
        let appName = '{{ getAppName() }}'
        let conversationId = '{{ $conversationId }}'
        let enableGroupSetting = '{{ isGroupChatEnabled() }}'
        let authRole = "{{ Auth::user()->role_name }}"

        /** Icons URL */
        let pdfURL = '{{ asset('assets/icons/pdf.png') }}'
        let xlsURL = '{{ asset('assets/icons/xls.png') }}'
        let textURL = '{{ asset('assets/icons/text.png') }}';
        let docsURL = '{{ asset('assets/icons/docs.png') }}'
        let videoURL = '{{ asset('assets/icons/video.png') }}'
        let youtubeURL = '{{ asset('assets/icons/youtube.png') }}'
        let audioURL = '{{ asset('assets/icons/audio.png') }}'
        let zipURL = '{{ asset('assets/icons/zip.png') }}'
        let isUTCTimezone = '{{ config('app.timezone') == 'UTC' ? 1 : 0 }}'
        let timeZone = '{{ config('app.timezone') }}'
        let blockedUsersListObj = JSON.parse('{!! json_encode($blockUserIds) !!}')
        let myContactIdsObj = JSON.parse('{!! json_encode($myContactIds) !!}')
        let groupMembers = []
        let checkShowNameChat = "{{ checkShowNameChat() }}"
    </script>
    <script src="{{ mix('assets/js/chat.js') }}"></script>
@endsection
