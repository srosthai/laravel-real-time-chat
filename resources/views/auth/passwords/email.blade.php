@extends('layouts.auth_layout')
@section('title')
    {{ __('messages.forget_password') }}
@endsection
@section('meta_content')
    - {{ __('messages.request_for_password_reset_link') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/simple-line-icons.css')}}">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="p-4 account-container w-100">
                <div class="card p-sm-4 p-3 login-group border-0">
                    <div class="card-body p-1">
                        @include('flash::message')
                        <form method="post" action="{{ url('/password/email') }}" id="forgetPasswordForm">
                            {{ csrf_field() }}
                            <h1 class="login-group__title mb-3">{{ __('messages.forgot_your_password') }}</h1>
                            <p class="text-muted login-group__sub-title mb-4">{{ __('messages.enter_email_to_reset_password') }}</p>
                            <div class="input-group mb-4">
                                <input type="email"
                                       class="form-control login-group__input {{ $errors->has('email')?'is-invalid':'' }}"
                                       name="email" value="{{ old('email') }}" id="email"
                                       placeholder="{{ __('messages.email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap forgot-pw-block">
                                <a href="{{ url('/login') }}"
                                   class="text-center back-to-login__btn text-decoration-none">Back To Login</a>
                                <button type="button" id="forgetPasswordBtn"
                                        class="btn btn-primary login-group__register-btn float-end ms-auto">
                                    <i class="fa fa-btn fa-envelope me-2"></i> {{ __('messages.reset_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
