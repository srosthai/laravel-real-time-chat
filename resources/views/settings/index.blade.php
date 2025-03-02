@extends('layouts.app')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header">
                            <div class="pull-left page__heading my-2">
                                {{ __('messages.settings') }}
                            </div>
                        </div>
                        <div class="card-body">
                            @include('coreui-templates::common.errors')
                            <form method="post" enctype="multipart/form-data" id="settingForm" action="{{ route('settings.update') }}">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                        <!-- App Name Field -->
                                    <div class="col-md-6">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('app_name', __('messages.app_name').':' ) !!}<span class="red">*</span>
                                            {!! Form::text('app_name', $settings['app_name'] ?? '', ['class' => 'form-control login-group__input', 'required','placeholder'=> __('messages.app_name')]) !!}
                                        </div>
                                    </div>
                                    <!-- Company Name Field -->
                                    <div class="col-md-6">
                                        <div class="form-group login-group__sub-title">
                                            {!! Form::label('company_name', __('messages.company_name').':' ) !!}<span class="red">*</span>
                                            {!! Form::text('company_name', $settings['company_name'] ?? '', ['class' => 'form-control login-group__input', 'required','placeholder'=> __('messages.company_name')]) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Is Active Field -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class=""><label class="login-group__sub-title">{{ __('messages.enable_group_chat').':' }}</label></div>
                                                <label class="switch switch-label switch-outline-primary-alt">
                                                    <input name="enable_group_chat" class="switch-input enable_group_chat not-checkbox"
                                                           type="checkbox" value="1" {{$enabledGroupChat}}>
                                                    <span class="switch-slider" data-checked="&#x2713;"
                                                          data-unchecked="&#x2715;"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class=""><label class="login-group__sub-title">{{ __('messages.members_can_add_group').':' }}</label></div>
                                                <label class="switch switch-label switch-outline-primary-alt">
                                                    <input name="members_can_add_group" class="switch-input members_can_add_group not-checkbox"
                                                           type="checkbox" value="1" {{$membersCanAddGroup}}>
                                                    <span class="switch-slider" data-checked="&#x2713;"
                                                          data-unchecked="&#x2715;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt-2 d-flex flex-wrap align-items-center">
                                            <span class="mt-2 user__upload-btn">
                                                <label class="btn btn-primary">
                                                    {{ __('messages.notification_sound') }}
                                                    <input id="notification_sound" class="d-none" name="notification_sound"
                                                           type="file" accept="audio/*">
                                                </label>
                                            </span>
                                            <span class="audio-control">
                                                <audio controls>
                                                    <source src="{{ isset($settings['notification_sound']) ? $settings['notification_sound'] : '' }}" type="audio/mpeg">
                                                </audio>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-2 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('messages.upload_logo') }}
                                                    <input id="logo_upload" class="d-none" name="app_logo" type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="profile__logo-img-wrapper">
                                                <img src="{{ !empty($settings['logo_url']) ? $settings['logo_url'] : asset('assets/images/logo.png') }}"
                                                     alt="" id="logo-img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-2 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('messages.upload_favicon') }}
                                                    <input id="favicon_upload" class="d-none" name="favicon_logo"
                                                           type="file" accept="image/*">
                                                </label>
                                            </div>
                                            <div class="profile__favicon-img-wrapper">
                                                <img
                                                        src="{{ !empty($settings['favicon_url']) ? url('/uploads').'/'.$settings['favicon_url'] : asset('assets/images/logo-30x30.png') }}"
                                                        alt="" id="favicon-img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class=""><label
                                                    class="login-group__sub-title">{{ __('messages.setting.pwa_icon').':' }}
                                                <span class="red">*</span></label><i
                                                    class="fa fa-question-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                    title="PWA icon must be 512x512"></i>
                                        </div>
                                        <div class="form-group mt-2 d-flex flex-wrap">
                                            <div class="mt-2 user__upload-btn w-auto me-sm-4 me-2">
                                                <label class="btn btn-primary">
                                                    {{ __('messages.setting.upload_icon') }}
                                                    <input id="pwaIcon" class="d-none" name="pwa_icon" type="file"
                                                           accept="image/*">
                                                </label>
                                            </div>
                                            <div class="pwa-icon-wrapper mt-1">
                                                <img
                                                        src="{{ !empty($settings['pwa_icon']) ? url($settings['pwa_icon']) : asset('assets/images/logo.png') }}"
                                                        alt="" id="pwa-icon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class=""><label
                                                    class="login-group__sub-title">{{ __('messages.show_profile_name_on_the_chat').':' }}</label>
                                        </div>
                                        <label class="switch switch-label switch-outline-primary-alt">
                                            <input name="show_name_chat" class="switch-input not-checkbox"
                                                   type="checkbox"
                                                   value="1" {{ isset($settings['show_name_chat']) && $settings['show_name_chat'] == 1 ? 'checked' : ''  }}>
                                            <span class="switch-slider" data-checked="&#x2713;"
                                                  data-unchecked="&#x2715;"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    {{ Form::button(__('messages.save') , ['type'=>'submit','class' => 'btn btn-primary me-1','id'=>'btnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/admin/users/edit_user.js') }}"></script>
@endsection
