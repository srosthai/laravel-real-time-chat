<div class="relative">
    <div class="row new-group-members">
        <div class="col-lg-6 col-12 new-members mb-lg-0 mb-2">
            <input
                type="search"
                class="form-control login-group__input mb-2"
                placeholder="{{ __('messages.placeholder.search_members') }}"
                wire:model="searchTerm"
                id="searchEditGroupMember"
            />
            <ul class="absolute z-10 list-group bg-white w-full group-members-list new-group-members__list">
                @foreach($contacts as $i => $contact)
                    <li class="list-group-item group-members-list-chat-select__list-item align-items-center d-flex justify-content-between">
                        <div class="d-flex w-100 align-items-center">
                            <input type="hidden" class="add-group-user-id" value="{{ $contact['id'] }}">
                            <div class="new-conversation-img-status position-relative me-2 user-{{ $contact['id'] }}"
                                 data-status="0" data-is-group="1">
                                <div class="new-conversation-img-status__inner">
                                    <img src="{{ $contact['photo_url'] }}" alt="user-avatar-img"
                                         class="user-avatar-img add-user-img">
                                </div>
                            </div>
                            <div class="truncate-button">
                                <div
                                    class="add-user-contact-name align-self-center text-truncate">{{ $contact['name'] }}</div>
                                <div class="align-self-center text-truncate">{{ $contact['email'] }}</div>
                            </div>
                            <button class="btn btn-sm btn-success float-end add-group-member ms-auto"
                                    data-id="{{ $contact['id'] }}">Add
                            </button>
                        </div>
                    </li>
                @endforeach
                <div class="text-center no-member-found h-110 {{ count($contacts) > 0 ? 'd-none' : '' }}">
                    <div class="chat__not-selected">
                        <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                        </div>
                        <span>{{__('messages.no_member_found')}}</span>
                    </div>
                </div>
            </ul>
        </div>
        <div class="col-lg-6 col-12 added-members pt-lg-0 pt-2" wire:ignore>
            <ul class="absolute z-10 list-group bg-white w-full added-group-members-list new-group-members__added-member-list">
                <div class="text-center no-member-added h-130">
                    <div class="chat__not-selected not-selected">
                        <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                        </div>
                        <span>{{__('messages.no_member_added_yet')}}</span>
                    </div>
                </div>
            </ul>
        </div>
    </div>
    {!! Form::hidden('users', json_encode($members), ['id' => 'selectedGroupMembersForEdit']) !!}
</div>
