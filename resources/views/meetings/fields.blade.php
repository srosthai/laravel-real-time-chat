<div class="form-group col-sm-6 login-group__sub-title">
    {!! Form::label('topic', __('messages.meeting.meeting_title').':' )!!}<span class="red">*</span>
    {!! Form::text('topic', isset($meeting) ? $meeting->topic : null, ['class' => 'form-control login-group__input', 'required','placeholder'=>__('messages.meeting.meeting_title')]) !!}
</div>

<div class="form-group col-sm-3 col-md-6 col-lg-6 col-xl-3 login-group__sub-title">
    {!! Form::label('start_time', __('messages.meeting.meeting_date').':' )!!}<span class="red">*</span>
    {!! Form::text('start_time', isset($meeting) ? $meeting->start_time : null, ['class' => 'form-control start-time login-group__input', 'required', 'autocomplete' => 'off','placeholder'=>__('messages.meeting.meeting_date')]) !!}
</div>

<div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-3">
    {!! Form::label('time_zone', __('messages.meeting.time_zone'),['class' => 'login-group__sub-title']).':' !!}<span class="red">*</span>
    {!! Form::select('time_zone', $timeZones, isset($meetingTimeZone) ? $meetingTimeZone : null, ['class' => 'form-control time-zone', 'placeholder' => __('messages.placeholder.select_time_zone'), 'required' ]) !!}
</div>

<div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-3 login-group__sub-title">
    {!! Form::label('duration', __('messages.meeting.meeting_duration').':')!!}<span class="red">*</span>
    {!! Form::text('duration', isset($meeting) ? $meeting->duration : null, ['class' => 'form-control login-group__input', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','placeholder'=>__('messages.meeting.meeting_duration')]) !!}
</div>

<div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-3 login-group__sub-title">
    {!! Form::label('members',__('messages.meeting.staff_list').':')!!}<span class="red">*</span>
    {!! Form::select('members[]', $users, isset($members) ? $members : null, ['class' => 'form-control members', 'multiple', 'required']) !!}
</div>

<div class="form-group col-sm-6 col-lg-6 col-xl-3">
    <label class="login-group__sub-title">{{ __('messages.meeting.host_video').':' }}</label>
    <span class="red">*</span>
    <div class="d-flex login-group__input align-items-center">
        <div class="custom-control custom-radio mx-2">
            <input type="radio" id="host-enabled" class="custom-control-input" name="host_video" value="1"
                   {{ isset($meeting) ? ($meeting->host_video == 1 ? 'checked' : '') : null }} required>
            <label class="custom-control-label" for="host-enabled"> {{__('messages.meeting.enabled')}}&nbsp; </label>
        </div>
        <div class="custom-control custom-radio mx-2">
            <input type="radio" id="host-disabled" class="custom-control-input" name="host_video"
                   {{ isset($meeting) ? ($meeting->host_video == 0 ? 'checked' : '') : null }} value="0"
                   required {{ empty($meeting) ? 'checked' : '' }}>
            <label class="custom-control-label" for="host-disabled"> {{__('messages.meeting.disabled')}}&nbsp; </label>
        </div>
    </div>
</div>

<div class="form-group col-sm-6 col-lg-6 col-xl-3">
    <label class="login-group__sub-title">{{ __('messages.meeting.client_video').':' }}</label>
    <span class="red">*</span>
    <div class="d-flex login-group__input align-items-center">
        <div class="custom-control custom-radio mx-2">
            <input type="radio" id="enabled" class="custom-control-input" name="participant_video"
                   {{ isset($meeting) ? ($meeting->participant_video == 1 ? 'checked' : '') : null }} value="1"
                   required>
            <label class="custom-control-label" for="enabled"> {{__('messages.meeting.enabled')}}&nbsp; </label>
        </div>
        <div class="custom-control custom-radio mx-2">
            <input type="radio" id="disabled" class="custom-control-input"
                   {{ isset($meeting) ? ($meeting->participant_video == 0 ? 'checked' : '') : null }} name="participant_video"
                   value="0" required {{ empty($meeting) ? 'checked' : '' }}>
            <label class="custom-control-label" for="disabled"> {{__('messages.meeting.disabled')}}&nbsp; </label>
        </div>
    </div>
</div>

<div class="form-group col-sm-12 login-group__sub-title">
    {!! Form::label('agenda',__('messages.meeting.description').':')!!}<span class="red">*</span>
    {{ Form::textarea('agenda', null, ['class' => 'form-control login-group__input', 'required','placeholder'=>__('messages.meeting.description')]) }}
</div>

<div class="form-group col-sm-12 mb-0">
    {{ Form::button(__('messages.save') , ['type'=>'submit','class' => 'btn btn-primary me-1','id'=>'btnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
    <a href="{{ route('meetings.index') }}"
            class="btn btn-secondary">{{ __('messages.cancel') }}</a>
</div>
