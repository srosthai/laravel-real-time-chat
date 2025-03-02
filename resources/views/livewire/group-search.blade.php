<div>
    <form class="mb-2">
        <input type="search" class="form-control search-input login-group__input" id="searchGroupsForChat"
               placeholder="{{ __('messages.search') }}..." wire:model="searchTerm">
    </form>
    <div id="divGroupListForChat">
        <ul class="list-group user-list-chat-select list-without-filter" id="groupListForChat">
            @foreach($groups as $key => $group)
                <li class="list-group-item user-list-chat-select__list-item align-items-center group-list-{{ $group->id }}">
                    <input type="hidden" class="add-chat-user-id" value="{{ $group->id }}">
                    <div class="new-conversation-img-status position-relative me-2 user-{{ $group->id }}"
                         data-status="0" data-is-group="1">
                        <div class="new-conversation-img-status__inner">
                            <img src="{{ $group->photo_url }}" alt="user-avatar-img"
                                 class="user-avatar-img add-user-img">
                        </div>
                    </div>
                    <span class="add-user-contact-name text-truncate">{{ $group->name }}</span>
                </li>
            @endforeach
        </ul>
        <div class="text-center no-group new-conversation__no-group @if(count($groups) > 0) d-none @endif">
            <div class="chat__not-selected">
                <div class="text-center"><i class="fa fa-2x fa-users" aria-hidden="true"></i>
                </div>
                {{ ($groupsCount > 0) ? __('messages.no_group_found') : __('messages.no_group_yet') }}
            </div>
        </div>
    </div>
</div>
