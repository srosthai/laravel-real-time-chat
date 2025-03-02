@extends('layouts.app')
@section('title')
    {{ __('messages.new_role') }}
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
                            <div class="pull-left page__heading">
                                {{ __('messages.new_role') }}
                            </div>
                        </div>
                        <div class="card-body py-sm-3 py-1">
                            @include('coreui-templates::common.errors')
                            {{ Form::open(['id'=>'createRoleForm', 'route' => 'roles.store', 'method' => 'post']) }}
                                {{ csrf_field() }}
                                <div class="row mb-sm-0 mb-1">
                                    @include('roles.fields')
                                </div>

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/admin/roles/create_edit_role.js') }}"></script>
    <script src="{{ mix('assets/js/custom.js') }}"></script>
@endsection
