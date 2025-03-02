<div>
<form class="mb-2">
    <input type="search" class="form-control search-input login-group__input" id="searchMyContactForChat"
           placeholder="{{ __('messages.search') }}..." wire:model="searchTerm">
</form>
<div class="form-group">
    <div class="col-sm-12">
        <div class="custom-control custom-checkbox contact-checkbox">
            <input name="my_contacts_filter" value="1" type="checkbox" class="custom-control-input group-type not-checkbox"
                   id="male" wire:model="male">
            <label class="custom-control-label" for="male">{{ __('messages.male') }}</label>
        </div>
        <div class="custom-control custom-checkbox contact-checkbox">
            <input name="my_contacts_filter" value="2" type="checkbox" class="custom-control-input group-type not-checkbox"
                   id="female" wire:model="female">
            <label class="custom-control-label" for="female">{{ __('messages.female') }}</label>
        </div>
        <div class="custom-control custom-checkbox contact-checkbox">
            <input name="my_contacts_filter" value="3" type="checkbox" class="custom-control-input group-type not-checkbox"
                   id="online" wire:model="online">
            <label class="custom-control-label" for="online">{{ __('messages.online') }}</label>
        </div>
        <div class="custom-control custom-checkbox contact-checkbox">
            <input name="my_contacts_filter" value="4" type="checkbox" class="custom-control-input group-type not-checkbox"
                   id="offline" wire:model="offline">
            <label class="custom-control-label" for="offline">{{ __('messages.offline') }}</label>
        </div>
    </div>
</div>
    <div id="myContactListForAddPeople">
        <ul class="list-group user-list-chat-select list-with-filter" id="myContactListForChat">
            @foreach($users as $key => $user)
                <li class="list-group-item user-list-chat-select__list-item align-items-center chat-user-{{ $user->id }} {{ getGender($user->gender) }} {{ getOnOffClass($user->is_online) }}">
                    <input type="hidden" class="add-chat-user-id" value="{{ $user->id }}">
                    <div class="new-conversation-img-status position-relative me-2 user-{{ $user->id }}"
                         data-status="{{$user->is_online}}">
                        <div
                            class="chat__person-box-status @if ($user->is_online) chat__person-box-status--online @else chat__person-box-status--offline @endif"></div>
                        <div class="new-conversation-img-status__inner">
                            <img src="{{$user->photo_url}}" alt="user-avatar-img" class="user-avatar-img add-user-img">
                        </div>
                    </div>
                    <div class="truncate-div">
                    <span class="add-user-contact-name text-truncate">{{ $user->name }}
                        <span class="my-contact-user-status"
                              data-status="{{ checkUserStatusForGroupMember($user->userStatus) }}">
                        @if (checkUserStatusForGroupMember($user->userStatus))
                            <i class="nav-icon user-status-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                               title="{{$user->userStatus->status}}" data-original-title="{{$user->userStatus->status}}">
                                {!! $user->userStatus->emoji  !!}
                            </i>
                        @endif
                        </span>
                    </span>
                    <div class="align-self-center add-user-email text-truncate">{{ $user->email }}</div>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="text-center no-my-contact new-conversation__no-my-contact @if(count($users) > 0) d-none @endif">
        <div class="chat__not-selected">
            <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
            </div>
            {{ ($myContactsCount > 0) ? __('messages.no_user_found') : __('messages.no_conversation_added_yet') }}
        </div>
    </div>
</div>
</div>
