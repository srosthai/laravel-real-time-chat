<!DOCTYPE html>
<html>

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title') | {{ getAppName() }}</title>
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/x-icon">
    <meta name="description" content="@yield('title') - {{ getAppName() }}">
    <meta name="keyword" content="CoreUI,Bootstrap,Admin,Template,InfyOm,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- PWA  -->
    <meta name="theme-color" content="#009ef7" />
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <!-- Bootstrap 4.1.3 -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
    <link rel="stylesheet" href="{{ mix('assets/css/coreui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icheck/skins/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datetime-picker.css') }}" />
    <link rel="stylesheet" href="{{ mix('assets/css/jquery.toast.min.css') }}">
    <script src="{{ mix('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    @if (getLoggedInUser()->is_subscribed)
        @include('layouts.one_signal')
    @endif
    @livewireStyles
    @routes
    <link rel="stylesheet" href="{{ mix('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/emojionearea.min.css') }}">
    @yield('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/style.css') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ mix('assets/css/custom-style.css') }}">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <header class="app-header navbar">
        @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
            <button class="navbar-toggler sidebar-toggler d-lg-none me-auto" type="button" data-toggle="sidebar-show">
                <i class="fa fa-angle-right header-arrow-small" aria-hidden="true"></i>
                <i class="fa fa-chevron-right header-arrow" aria-hidden="true"></i>
            </button>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                <i class="fa fa-angle-right header-arrow-small" aria-hidden="true"></i>
                <i class="fa fa-chevron-right header-arrow" aria-hidden="true"></i>
            </button>
        @endif
        <ul class="nav navbar-nav ms-auto">
            <li class="nav-item dropdown notification">
                <a class="nav-link notification__icon" data-bs-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <span class="totalNotificationCount" data-total_count="0"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right notification__popup">
                    <div class="dropdown-header text-center">
                        <div class="header-heading">
                            <strong>{{ __('messages.notifications') }}</strong>
                            <span class="totalNotificationCount" data-total_count="0" style="display: none"></span>
                        </div>
                        <div class="header-button">
                            <a href="#" class="read-all-notification">{{ __('messages.read_all') }}</a>
                        </div>
                    </div>
                    <a class="dropdown-item read" id="noNewNotification">
                        {{ __('messages.no_notification_yet') }}
                    </a>
                </div>
            </li>
            @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
                <li class="dropdown language-menu no-hover me-3">
                    <a href="#" class="dropdown-toggle text-success text-decoration-none"
                        data-bs-toggle="dropdown" role="button">
                        {{ strtoupper(getCurrentLanguageName()) }}&nbsp;
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-right" role="menu">
                        @foreach (getUserLanguages() as $key => $value)
                            <span class="language-item"><a href="javascript:void(0)"
                                    class="changeLanguage dropdown-item"
                                    data-prefix-value="{{ $key }}">{{ $value }}</a></span>
                        @endforeach
                    </div>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link avatar-name" style="margin-right: 10px" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="profile-name">{{ htmlspecialchars_decode(getLoggedInUser()->name) ?? '' }}</span>
                    <img class="img-avatar" src="{{ getLoggedInUser()->photo_url }}" alt="InfyOm">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center dropdown-text">
                        <strong>{{ __('messages.account') }}</strong>
                    </div>
                    <a class="dropdown-item" href="{{ url('/profile') }}">
                        <i class="fa fa-user"></i>{{ __('messages.edit_profile') }}</a>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#changePasswordModal"><i
                            class="fa fa-lock"></i>{{ __('messages.change_password') }}</a>
                    @if (session('impersonated_by'))
                        <a class="dropdown-item" href="{{ route('impersonate.userLogout') }}">
                            <i class="fa fa-external-link"></i>{{ __('messages.back_to_admin') }}</a>
                    @endif
                    @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#setCustomStatusModal">
                        <i class="fa fa-smile-o"></i>{{ __('messages.set_custom_status') }}</a>
                    @endif
                    <a class="dropdown-item" class="btn btn-default btn-flat"
                        onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i>{{ __('messages.logout') }}
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </header>
    <div class="app-body">
        @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
            @include('layouts.sidebar')
        @endif
        @include('layouts.change_password')
        <main class="main">
            <audio id="notificationSound">
                <source src="{{ getNotificationSound() ? getNotificationSound() : '' }}" type="audio/mp3">
            </audio>
            @yield('content')
        </main>
    </div>
    @include('chat.templates.notification')
    @include('partials.file-upload')
    @include('partials.set_custom_status_modal')
    @if (Auth::user()->hasRole('Admin') && Auth::user()->hasPermissionTo('manage_meetings'))
        <footer class="app-footer">
            <div class="d-flex justify-content-between w-100">
                <div>
                    <a href="https://chat.infyom.com/">{{ getAppName() }}</a>
                    <span>&copy;{{ date('Y') }} {{ getCompanyName() }}.</span>
                </div>
                @if (config('app.show_version'))
                    <span class="text-right">v{{ version() }}</span>
                @endif
            </div>
        </footer>
    @endif
</body>
<!-- jQuery 3.1.1 -->
<script src="{{ asset('messages.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ mix('assets/js/coreui.min.js') }}"></script>
<script src="{{ mix('assets/js/jquery.toast.min.js') }}"></script>
<script src="{{ mix('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/moment-timezone.min.js') }}"></script>
<script src="{{ asset('assets/icheck/icheck.min.js') }}"></script>
<script src="https://www.jsviews.com/download/jsviews.min.js"></script>
<script src="{{ asset('js/emojionearea.js') }}"></script>
<script src="{{ mix('assets/js/emojione.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datetime-picker.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('sw.js') }}"></script>
<script>
    $(document).ready(function() {
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register('sw.js').then(function(reg) {
            console.log('Service worker has been registered for scope: ' + reg.scope)
        })
    }
</script>
@yield('page_js')
<script>
    let currentLocale = "{{ Config::get('app.locale') }}"
    if (currentLocale == '') {
        currentLocale = 'en'
    }
    Lang.setLocale(currentLocale)
    let pusherKey = '{{ config('broadcasting.connections.pusher.key') }}'
    let pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') }}'
    let messageDeleteTime = '{{ config('configurable.delete_message_time') }}'
    let baseURL = '{{ url('/') }}'
    let conversationsURL = '{{ route('conversations') }}'
    let notifications = JSON.parse(JSON.stringify({!! json_encode(getNotifications()) !!}))
    let loggedInUserId = '{{ getLoggedInUserId() }}'
    let loggedInUserStatus = '{!! getLoggedInUser()->userStatus !!}'
    if (loggedInUserStatus != '') {
        loggedInUserStatus = JSON.parse(JSON.stringify({!! getLoggedInUser()->userStatus !!}))
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });
    (function($) {
        $.fn.button = function(action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true)
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
    $(document).ready(function() {
        $('.alert').delay(4000).slideUp(300);
    });
</script>
<script src="{{ mix('assets/js/app.js') }}"></script>
<script src="{{ mix('assets/js/custom.js') }}"></script>
<script src="{{ mix('assets/js/notification.js') }}"></script>
<script src="{{ mix('assets/js/set_user_status.js') }}"></script>
<script src="{{ mix('assets/js/set-user-on-off.js') }}"></script>
<script src="{{ mix('assets/js/profile.js') }}"></script>
@livewireScripts
@stack('scripts')
@yield('scripts')

</html>
