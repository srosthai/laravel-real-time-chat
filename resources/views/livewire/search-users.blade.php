<div>
    <form class="mb-2">
        <input type="search" class="form-control search-input login-group__input" id="searchContactForChat"
               placeholder="{{ __('messages.search') }}..." wire:model="searchTerm">
    </form>
    <div class="form-group">
        <div class="col-sm-12 d-flex justify-content-around">
            <div class="custom-control custom-checkbox">
                <input name="new_contact_gender" value="1" type="checkbox" class="custom-control-input group-type not-checkbox" id="newContactMale" wire:model="male">
                <label class="custom-control-label" for="newContactMale">{{ __('messages.male') }}</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input name="new_contact_gender" value="2" type="checkbox"
                       class="custom-control-input group-type not-checkbox" id="newContactFemale" wire:model="female">
                <label class="custom-control-label" for="newContactFemale">{{ __('messages.female') }}</label>
            </div>
        </div>
    </div>
    <div id="userListForAddPeople">
        <ul class="list-group user-list-chat-select list-with-filter" id="userListForChat">
            @foreach($users as $key => $value)
                <li class="list-group-item user-list-chat-select__list-item align-items-center chat-user-{{ $value->id }} {{ getGender($value->gender) }}"
                    data-status="{{ $value->is_online }}" data-gender="{{$value->gender}}">
                    <input type="hidden" class="add-chat-user-id" value="{{ $value->id }}">
                    <div class="new-conversation-img-status position-relative me-2 user-{{ $value->id }}"
                         data-status="{{ $value->is_online }}">
                        <div
                            class="chat__person-box-status @if($value->is_online) chat__person-box-status--online @else chat__person-box-status--offline @endif"></div>
                        <div class="new-conversation-img-status__inner">
                            <img src="{{ $value->photo_url }}" alt="user-avatar-img"
                                 class="user-avatar-img add-user-img">
                        </div>
                    </div>
                    <div class="truncate-div">
                        <span
                            class="add-user-contact-name align-self-center fw-bolder text-truncate">{{ $value->name }}</span>
                        <div class="align-self-center add-user-email text-truncate">{{ $value->email }}</div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="text-center no-user new-conversation__no-user @if(count($users) > 0) d-none @endif">
            <div class="chat__not-selected">
                <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                </div>
                {{ __('messages.no_user_found') }}
            </div>
        </div>
    </div>
</div>
