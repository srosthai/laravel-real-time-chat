@extends('layouts.app')
@section('title')
    {{ __('messages.role_details') }}
@endsection
@section('page_css')
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
                        <div class="card-header page-header">
                            <div class="pull-left page__heading">
                                {{ __('messages.role_details') }}
                            </div>
                            <div class="text-end">
                                @if(!$role->is_default)
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                       class="btn btn-primary"> {{ __('messages.edit_role') }} </a>
                                @endif
                                <a type="button" href="{{ route('roles.index') }}"
                                   class="btn btn-secondary close_create_role ms-1"> {{ __('messages.back') }} </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.name'), ['class' => 'fw-bold login-group__sub-title']) }}
                                    <br>
                                    <span>{{ $role->name ?? 'N/A' }}</span>
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('permissions', __('messages.permissions'), ['class' => 'fw-bold login-group__sub-title']) }}
                                    <br>
                                    <div class="row col-12">
                                        @if($role->getAllPermissions()->count() > 0)
                                            @foreach($role->getAllPermissions()->pluck('display_name')->toArray() as $permission)
                                                <span
                                                    class="bg-regent-opacity-3 fs-3 p-1 me-4 mb-1 permission-lh">{{ $permission }}</span>
                                            @endforeach
                                        @else
                                            <span
                                                class="bg-regent-opacity-3 fs-3 p-1 me-4 mb-1 permission-lh">{{ __('messages.N/A') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
@endsection
@section('scripts')

@endsection
