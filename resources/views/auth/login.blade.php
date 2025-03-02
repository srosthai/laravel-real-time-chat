@extends('layouts.auth_layout')
@section('title')
    {{ __('messages.login') }}
@endsection
@section('meta_content')
    - {{ __('messages.login') }} {{ __('messages.to') }} {{ getAppName() }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="p-4 account-container w-100">
                <div class="card-group login-group overflow-hidden">
                    <div class="card p-sm-2 mb-0 login-group__card">
                        <div class="card-body login-group__login-body">
                            @if ($errors->any())
                                <div class="alert alert-danger text-center mt-2">{{ $errors->first() }}</div>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                            <form method="post" action="{{ route('login') }}" id="loginForm" class="login-group__form">
                                @csrf
                                <h1 class="login-group__title mb-2 text-center">{{ __('messages.login') }}</h1>
                                <div class="d-flex justify-content-center pb-2">
                                    <span class="me-2 login-group__sub-title">New Here?</span>
                                    <a class="login-group__sub-title register-link active d-flex
                                                    justify-content-center"
                                        href="{{ url('/register') }}">{{ __('messages.register_now!') }}</a>
                                </div>
                                <p class="text-muted login-group__sub-title mb-4 text-center">
                                    {{ __('messages.sign_in_to_your_account') }}</p>
                                <div class="form-group mb-4 login-group__sub-title">
                                    {!! Form::label('email', __('messages.email') . ':') !!}<span class="red">*</span>
                                    <input type="email" class="form-control login-group__input" name="email"
                                        value="{{ Cookie::get('email') !== null ? Cookie::get('email') : old('email') }}"
                                        placeholder="{{ __('messages.email') }}" id="email" required>
                                </div>
                                <div class="form-group mb-4 login-group__sub-title">
                                    {!! Form::label('password', __('messages.password') . ':') !!}<span class="red">*</span>
                                    <input type="password" class="form-control login-group__input"
                                        placeholder="{{ __('messages.password') }}" name="password" id="password"
                                        value="{{ Cookie::get('password') !== null ? Cookie::get('password') : null }}"
                                        onkeypress="return avoidSpace(event)" required>
                                </div>
                                <div class="input-group mb-3 justify-content-between">
                                    <div class="checkbox login-group__checkbox">
                                        <label class="d-flex align-items-center">
                                            <input type="checkbox" name="remember" class="me-2"
                                                {{ Cookie::get('remember') !== null ? 'checked' : '' }}>
                                            {{ __('messages.remember_me') }}
                                        </label>
                                    </div>
                                    <div class="text-end d-flex justify-content-end">
                                        <a class="btn btn-link px-0 py-0 login-group__sub-title register-link
                                                    text-decoration-none"
                                            href="{{ url('/password/reset') }}">
                                            {{ __('messages.forgot_password?') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="row flex-sm-row flex-column">
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 login-group__btn" type="button"
                                            id="loginBtn">{{ __('messages.login') }}</button>
                                    </div>

                                </div>
                            </form>
                            @if (
                                (!empty(config('services.google.client_id')) &&
                                    !empty(config('services.google.client_secret')) &&
                                    !empty(config('services.google.redirect'))) ||
                                    (!empty(config('services.facebook.client_id')) &&
                                        !empty(config('services.facebook.client_secret')) &&
                                        !empty(config('services.facebook.redirect'))))
                                <div class="login-group__line my-5 w-100 d-flex justify-content-center">
                                    <span class="d-flex text-muted justify-content-center align-items-center">Sign In
                                        With</span>
                                </div>
                                <div class="row mt-2 justify-content-around w-100 mx-auto flex-sm-row flex-column">
                                    @if (
                                        !empty(config('services.google.client_id')) &&
                                            !empty(config('services.google.client_secret')) &&
                                            !empty(config('services.google.redirect')))
                                        <div class="mb-sm-0 mb-3 col-sm-6 col-12 ps-md-1 ps-0 pe-md-2 pe-sm-1 pe-0">
                                            <div class="login-group__rounded py-2 px-3">
                                                <img src="{{ asset('assets/images/search.png') }}"
                                                    class="login-group__login-icon">
                                                <a href="{{ url('/login/google') }}"
                                                    class="text-decoration-none login-group__hover">
                                                    {{ __('messages.login_with_google') }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        !empty(config('services.facebook.client_id')) &&
                                            !empty(config('services.facebook.client_secret')) &&
                                            !empty(config('services.facebook.redirect')))
                                        <div class="col-sm-6 col-12 ps-md-2 ps-sm-1 ps-0 pe-md-1 pe-0">
                                            <div class="login-group__rounded py-2 px-3">
                                                <img src="{{ asset('assets/images/facebook.png') }}"
                                                    class="login-group__login-icon">
                                                <a href="{{ url('/login/facebook') }}"
                                                    class="text-decoration-none login-group__hover">
                                                    {{ __('messages.login_with_facebook') }}</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card text-white bg-primary py-5 d-md-down-none text-center d-none">
                        <div class="row card-body text-center h-100">
                            <div class="col-12 sign-up-div">
                                <h1 class="login-group__signup-text">{{ __('messages.sign_up') }}</h1>
                                <p class="login-group__signup-subtext mt-4">{{ __('messages.sign_up_msg') }}</p>
                                <a class="btn btn-primary active mt-3 py-2 mt-4 d-flex justify-content-center"
                                    href="{{ url('/register') }}">{{ __('messages.register_now!') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
