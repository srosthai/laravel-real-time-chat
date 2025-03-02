@extends('layouts.app')
@section('title')
    {{__('messages.roles')}}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header flex-wrap">
                            <div class="pull-left page__heading me-3 my-2">
                                {{ __('messages.roles') }}
                            </div>
                            <a href="{{ route('roles.create') }}"
                               class="my-2 pull-right btn btn-primary ms-auto">{{ __('messages.new_role') }}</a>
                        </div>
                        <div class="card-body">
                            @include('roles.table')
                            <div class="pull-right me-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
    <script type="text/javascript" src="{{ asset('js/dataTable.min.js') }}"></script>
    <script src="{{ mix('assets/js/jquery.toast.min.js') }}"></script>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/custom-datatables.js') }}"></script>
    <script>
        let token = '{{ csrf_token() }}'
        let AuthUserRoleId = "{{ isset(getLoggedInUser()->roles) ? getLoggedInUser()->roles->first()->id : '' }}"
    </script>
    <script src="{{ mix('assets/js/admin/roles/role.js') }}"></script>
    <script src="{{ mix('assets/js/custom.js') }}"></script>
@endsection

