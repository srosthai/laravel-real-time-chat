<div class="form-group col-sm-12">
    <div class="alert alert-danger" style="display: none" id="validationErrorsBox"></div>
</div>
<div class="form-group col-md-6 col-sm-12 login-group__sub-title">
    {{ Form::label('name', __('messages.name').':') }}<span class="red">*</span>
    {{ Form::text('name', null, ['class' => 'form-control login-group__input', 'id' => 'role_name', 'required','placeholder'=>__('messages.name')]) }}
</div>
<div class="form-group col-md-6 col-sm-12">
    {{ Form::label('permissions', __('messages.permissions').':', ['class' => 'login-group__sub-title']) }}<span class="red">*</span>
    <br>
    <div class="row px-3">
        @foreach($permissions->get() as $permission)
            <div class="custom-control custom-checkbox mb-2">
                <input id="{{ $permission->name }}" class="custom-control-input not-checkbox role-permission"
                       type="checkbox" name="permissions[]"
                       value="{{ $permission->name }}">
                <label for="{{ $permission->name }}"
                       class="custom-control-label">{{ $permission->display_name }}</label>
            </div>
        @endforeach
    </div>
</div>
<div class="text-start form-group col-sm-12">
    {{ Form::button(__('messages.save') , ['type'=>'submit','class' => 'btn btn-primary primary-btn','id'=>'btnCreateRole','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
    <a type="button" href="{{ route('roles.index') }}" id="btnRoleCancel"
       class="btn btn-secondary close_create_role ms-1">{{ __('messages.cancel') }}
    </a>
</div>
