@extends('layouts.app')
@section('title')
    {{ __('messages.users') }}
@endsection
@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div
                            class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('messages.users') }}
                                </div>
                                <button type="button"
                                        class="my-2 pull-right btn btn-primary filter-container__btn ms-sm-0 ms-auto d-sm-none d-block"
                                        data-bs-toggle="modal"
                                        data-bs-target="#create_user_modal">{{ __('messages.new_user') }}</button>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    {!!Form::select('drp_users', \App\Models\User::FILTER_ARRAY, null, ['id' => 'filter_user','class'=>'form-control','placeholder' => __('messages.placeholder.select_status_all'),'style'=>'min-width:150px;'])  !!}
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    {!!Form::select('privacy_filter', \App\Models\User::PRIVACY_FILTER_ARRAY, null, ['id' => 'privacy_filter', 'class'=>'form-control','placeholder' => __('messages.placeholder.select_privacy'), 'style'=>'min-width:150px;'])  !!}
                                </div>
                                <button type="button"
                                        class="my-2 pull-right btn btn-primary new-user-btn filter-container__btn ms-sm-0 ms-auto"
                                        data-bs-toggle="modal"
                                        data-bs-target="#create_user_modal">{{ __('messages.new_user') }}</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('users.table')
                            <div class="pull-right me-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('users.create')
    @include('users.edit')
    @include('users.templates.action_icons')
@endsection
@section('page_js')
    <script type="text/javascript" src="{{ asset('js/dataTable.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let defaultImageAvatar = "{{ getDefaultAvatar() }}"
    </script>
    <script src="{{ mix('assets/js/admin/users/user.js') }}"></script>
    <script src="{{ mix('assets/js/admin/users/edit_user.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom-datatables.js') }}"></script>
@endsection

