@extends('layouts.auth_layout')
@section('title')
    {{ __('messages.register') }}
@endsection
@section('meta_content')
    - {{ __('messages.register') }} {{ __('messages.to') }} {{ getAppName() }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/simple-line-icons.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="p-4 account-container w-100">
                <div class="card p-sm-4 p-3 login-group border-0">
                    @if ($errors->any())
                        <div class="alert alert-danger text-center mt-2">{{ $errors->first() }}</div>
                    @endif
                    <div class="card-body p-1">
                        <form method="post" action="{{ url('/register') }}" id="registerForm">
                            {{ csrf_field() }}
                            <h1 class="login-group__title mb-2">{{ __('messages.register') }}</h1>
                            <p class="text-muted login-group__sub-title mb-3">{{ __('messages.create_your_account') }}</p>
                            <div class="form-group mb-4 login-group__sub-title">
                                {!! Form::label('name', __('messages.full_name') . ':') !!}<span class="red">*</span>
                                <input type="text" class="form-control login-group__input" name="name"
                                    value="{{ old('name') }}" placeholder="{{ __('messages.full_name') }}"
                                    id="name" required>
                            </div>
                            <div class="form-group mb-4 login-group__sub-title">
                                {!! Form::label('email', __('messages.email') . ':') !!}<span class="red">*</span>
                                <input type="email" class="form-control login-group__input" name="email"
                                    value="{{ old('email') }}" placeholder="{{ __('messages.email') }}" id="email"
                                    required>
                            </div>
                            <div class="form-group mb-4 login-group__sub-title">
                                {!! Form::label('password', __('messages.password') . ':') !!}<span class="red">*</span>
                                <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Minimum 8 character required, White space is not allowed"></i>
                                <input type="password" class="form-control login-group__input" name="password"
                                    placeholder="{{ __('messages.password') }}" id="password"
                                    onkeypress="return avoidSpace(event)" required>
                            </div>
                            <div class="form-group mb-4 login-group__sub-title">
                                {!! Form::label('confirm_password', __('messages.confirm_password') . ':') !!}<span class="red">*</span>
                                <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Minimum 8 character required, White space is not allowed"></i>
                                <input type="password" name="password_confirmation" class="form-control login-group__input"
                                    placeholder="{{ __('messages.confirm_password') }}" id="password_confirmation"
                                    onkeypress="return avoidSpace(event)" required>
                            </div>
                            <button type="button" id="registerBtn"
                                class="btn btn-primary btn-block btn-flat mb-4 login-group__register-btn">{{ __('messages.register') }}</button>
                            <a href="{{ url('/login') }}"
                                class="text-center back-to-login__btn text-decoration-none">{{ __('messages.already_have_membership') }}</a>
                        </form>
                        <div id="registerSuccessMessage" class="alert alert-success mt-3" style="display: none;">
                            {{ __('messages.registration_successful') }}
                        </div>
                        <script>
                            document.getElementById('registerForm').addEventListener('submit', function(event) {
                                event.preventDefault();
                                document.getElementById('registerSuccessMessage').style.display = 'block';
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
    <script>
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
