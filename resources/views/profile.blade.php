@extends('layouts.app')
@section('title')
    {{ __('messages.edit_profile') }}
@endsection
@section('content')
    <div class="page-container">
        <div class="profile bg-white">
            <h1 class="page__heading text-center mb-4">{{ __('messages.edit_profile') }}</h1>
            {!! Form::open(['id'=>'editProfileForm','files'=>true]) !!}
            <div class="profile__inner m-auto">
                <div class="edit-profile-card w-100 mb-5 position-relative">
                    <div class="profile__img-wrapper mb-4">
                        <img src="{{ getLoggedInUser()->photo_url }}" alt="" id="upload-photo-img">
                    </div>
                    <div class="text-center mb-4 edit-profile-card__btn">
                        <label class="btn profile__update-label">{{ __('messages.upload_photo') }}
                            <input id="upload-photo" class="d-none" name="photo" type="file" accept="image/*">
                        </label>
                        @if(!(Str::contains(getLoggedInUser()->getOriginal('photo_url'),'ui-avatars.com')) && !(Str::contains(getLoggedInUser()->getOriginal('photo_url'),'assets')))
                            <label>
                                <button class="btn btn-danger mb-2 remove-profile-img ms-1">{{__('messages.remove_profile')}}</button>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="alert alert-danger w-100" style="display: none" id="editProfileValidationErrorsBox"></div>
                <div class="form-group bordered-input w-100">
                    <label for="user-name" class="mb-2 login-group__sub-title">{{ __('messages.name').':' }}<span
                                class="profile__required">*</span></label>
                    <input type="text" class="profile__name login-group__input form-control" id="user-name"
                           aria-describedby="User name" placeholder="{{ __('messages.name') }}"
                           value="{{ (htmlspecialchars_decode(getLoggedInUser()->name))??'' }}" name="name" required>
                </div>
                <div class="form-group bordered-input w-100">
                    <label for="email" class="mb-2 login-group__sub-title">{{ __('messages.email').':' }}<span
                                class="profile__required">*</span></label>
                    <input type="email" class="profile__email login-group__input form-control" id="email"
                           aria-describedby="User email" placeholder="{{ __('messages.email') }}"
                           value="{{getLoggedInUser()->email}}" name="email" required>
                </div>
                <div class="form-group bordered-input w-100">
                    <label for="about" class="mb-2 login-group__sub-title">{{ __('messages.bio').':' }}</label>
                    <textarea
                            class="profile__email login-group__input form-control" id="about" rows="3"
                            name="about" placeholder="{{ __('messages.bio')}}">{{ (htmlspecialchars_decode(Auth::user()->about))??'' }}</textarea>
                </div>
                <div class="form-group bordered-input w-100">
                    <label for="phone" class="mb-2 login-group__sub-title">
                        {{ __('messages.phone').':' }}</label>
                    <input type="tel" class="profile__phone form-control login-group__input" id="phone"
                           aria-describedby="User phone no"
                           placeholder="{{ __('messages.phone_number') }}"
                           name="phone"
                           value="{{getLoggedInUser()->phone}}">
                </div>
                @php
                    $isSubscribed = getLoggedInUser()->is_subscribed
                @endphp
                <div class="form-group w-100">
                    <div class="form-group w-100">
                        <label class="mb-2 login-group__sub-title">{{ __('messages.group.privacy').':' }}</label>
                        <div class="d-flex login-group__input align-items-center">
                            <div class="custom-control custom-radio mx-2">
                                <input type="radio" class="custom-control-input" id="privacyPrivate" name="privacy"
                                       value="0"
                                       @if(getLoggedInUser()->privacy == 0) checked @endif> <label
                                        class="custom-control-label"
                                        for="privacyPrivate">
                                    {{ __('messages.group.private') }}
                                    <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="Only My Contacts can chat with me"></i>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mx-2">
                                <input type="radio" class="custom-control-input" id="privacyPublic" name="privacy"
                                       value="1"
                                       @if(getLoggedInUser()->privacy == 1) checked @endif> <label
                                        class="custom-control-label"
                                        for="privacyPublic">
                                    {{ __('messages.group.public') }}
                                    <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Anyone can chat with me"
                                    ></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group w-100">
                        <label class="mb-2 login-group__sub-title">{{ __('messages.gender').':' }}</label>
                        <div class="d-flex login-group__input align-items-center">
                            <div class="custom-control custom-radio mx-2">
                                <input type="radio" class="custom-control-input" id="male" name="gender"
                                       value="{{ \App\Models\User::MALE }}"
                                       @if(getLoggedInUser()->gender == \App\Models\User::MALE) checked @endif>
                                <label class="custom-control-label" for="male">{{ __('messages.male') }}</label>
                            </div>
                            <div class="custom-control custom-radio mx-2">
                                <input type="radio" class="custom-control-input" id="female" name="gender"
                                       value="{{ \App\Models\User::FEMALE }}"
                                       @if(getLoggedInUser()->gender == \App\Models\User::FEMALE) checked @endif>
                                <label class="custom-control-label" for="female">{{ __('messages.female') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group bordered-input w-100">
                    <div class="form-group ps-0 d-flex align-items-center">
                        {!! Form::checkbox('is_subscribed',1,$isSubscribed, ['id' => 'webNotification', 'class' => 'not-checkbox custom-checked mr-1']) !!}
                        &nbsp;<lable for="is_subscribed"
                                     class="mb-0">{{__('messages.enable_onesignal_web_application')}}</lable>
                    </div>
                </div>
                <div class="d-flex w-100">
                    {!! Form::button(__('messages.save') , ['type'=>'submit','class' => 'btn btn-primary me-2 primary-btn','id'=>'btnEditSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) !!}
                    <a class="btn btn-secondary" id="cancelGroupModal"
                       href="{{url('conversations')}}">{{ __('messages.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
