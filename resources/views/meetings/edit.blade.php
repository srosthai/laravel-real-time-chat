@extends('layouts.app')
@section('title')
    {{ __('messages.edit_meeting') }}
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
                                {{ __('messages.edit_meeting') }}
                            </div>
                        </div>
                        <div class="card-body">
                            @include('coreui-templates::common.errors')
                                {{ Form::model($meeting, ['route' => ['meetings.update', $meeting->id], 'method' => 'put', 'id'=>'meetingForm']) }}
                                {{ csrf_field() }}
                                <div class="row">
                                    @include('meetings.fields')
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/admin/meetings/meetings.js') }}"></script>
@endsection
