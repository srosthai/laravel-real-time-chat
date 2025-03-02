<div class="sidebar">
    <nav class="sidebar-nav">
        <div class="d-flex align-items-center justify-content-between mb-3 overflow-hidden sidebar-inner-header">
            <a class="navbar-brand d-flex align-items-center" href="{{url('/')}}">
                {{-- <img class="navbar-brand-minimized me-4" src="{{ getThumbLogoUrl() }}" width="30" alt="Infyom Logo"
                     height="30" alt="{{config('app.name')}}"> --}}
                <span class="d-flex">
                    <span class="brand-name-infy">Chat</span><span class="brand-name-chat"></span>
                </span>
            </a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                <i class="fa fa-chevron-left toggle-arrow" aria-hidden="true"></i>
                <i class="fa fa-angle-left toggle-arrow-small" aria-hidden="true"></i>
            </button>
        </div>
        <ul class="nav">
            @include('layouts.menu')
        </ul>
    </nav>
</div>
