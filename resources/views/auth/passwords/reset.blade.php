@extends('layouts.auth_layout')
@section('title')
    {{ __('messages.reset_password') }}
@endsection
@section('meta_content')
    - {{ __('messages.reset_password') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/simple-line-icons.css')}}">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mx-4">
                <div class="card-body p-4">
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{{Session::get('error')}}</div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{Session::get('success')}}</div>
                    @endif
                    <form method="post" action="{{ url('/password/reset') }}" id="resetPasswordForm">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{$token}}">
                        <h1>{{ __('messages.reset_password') }}</h1>
                        <p class="text-muted">{{ __('messages.enter_email_and_new_password') }}</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">@</span>
                            </div>
                            <input type="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
                                   name="email" value="{{ (old('email')) ? old('email') : $email }}" id="email"
                                   placeholder="{{ __('messages.email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="icon-lock"></i>
                              </span>
                            </div>
                            <input type="password" class="form-control {{ $errors->has('password')?'is-invalid':''}}"
                                   name="password" id="password" placeholder="{{ __('messages.password') }}"
                                   onkeypress="return avoidSpace(event)">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="icon-lock"></i>
                              </span>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                   placeholder="{{ __('messages.confirm_password') }}" onkeypress="return avoidSpace(event)">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                               </span>
                            @endif
                        </div>
                        <button type="button" id="resetPasswordBtn" class="btn btn-block btn-primary btn-block btn-flat">
                            <i class="fa fa-btn fa-refresh"></i> {{ __('messages.reset') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
