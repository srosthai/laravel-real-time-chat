<div>
    <form class="mb-2">
        <input type="search" class="form-control search-input login-group__input" id="searchBlockUsers"
               placeholder="{{ __('messages.search') }}..." wire:model="searchTerm">
    </form>
    <div id="divOfBlockedUsers">
        <ul class="list-group user-list-chat-select list-without-filter" id="blockedUsersList">
            @foreach($users as $key => $user)
                <li class="list-group-item blocked-user-list-chat-select__list-item blocked-user-{{ $user->id }} align-items-center d-flex justify-content-between px-sm-3 px-2">
                    <div class="d-flex w-100 align-items-center">
                        <div class="new-conversation-img-status position-relative me-2">
                            <div class="new-conversation-img-status__inner">
                                <img src="{{ $user->photo_url }}" alt="user-avatar-img"
                                     class="user-avatar-img add-user-img">
                            </div>
                        </div>
                        <div class="truncate-block-button">
                            <div class="add-user-contact-name align-self-center text-truncate">{{ $user->name }}</div>
                            <div class="align-self-center add-user-email text-truncate">{{ $user->email }}</div>
                        </div>
                        <button class="btn btn-success btn-unblock ms-auto text-truncate"
                                data-id="{{ $user->id }}">{{__('messages.unblock')}}</button>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="text-center no-blocked-user new-conversation__no-user @if(count($users) > 0) d-none @endif">
            <div class="chat__not-selected">
                <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                </div>
                <span id="noBlockedUsers">
                    {{ ($blockedUsersCount > 0) ? __('messages.no_blocked_user_found') : __('messages.no_users_blocked') }}
                </span>
            </div>
        </div>
    </div>
</div>
