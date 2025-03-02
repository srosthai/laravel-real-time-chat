<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger" style="display: none" id="validationErrorsBox"></div>
    </div>
    <div class="col-sm-12 d-flex flex-sm-row flex-column align-items-center">
        <div class="form-group">
            <div class="profile__inner m-auto">
                <div class="text-center profile__img-wrapper">
                    <img src="{{ isset($user->photo_url) ? $user->photo_url : getDefaultAvatar() }}" alt=""
                         id="upload-photo-img">
                </div>
                <div class="text-center mt-2 user__upload-btn">
                    <label class="btn profile__update-label">
                        {{ __('messages.upload_photo') }}
                        <input id="upload-photo" class="d-none" name="photo" type="file" accept="image/*">
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Name Field -->
            <div class="form-group col-lg-6 login-group__sub-title">
                {!! Form::label('name', __('messages.full_name').':' ) !!}<span class="red">*</span>
                {!! Form::text('name', null, ['class' => 'form-control login-group__input', 'id' => 'name', 'required','placeholder'=>__('messages.full_name')]) !!}
            </div>
            <!-- Phone Field -->
            <div class="form-group col-lg-6 login-group__sub-title">
                {!! Form::label('phone', __('messages.phone').':' ) !!}
                {!! Form::text('phone', null, ['class' => 'form-control login-group__input', 'id' => 'phone','placeholder'=> __('messages.phone')]) !!}
            </div>
            <!-- Email Field -->
            <div class="form-group col-12 login-group__sub-title">
                {!! Form::label('email', __('messages.email').':' ) !!}<span class="red">*</span>
                {!! Form::email('email', null, ['class' => 'form-control login-group__input', 'id' => 'email', 'required','placeholder'=>__('messages.email')]) !!}
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <!-- Bio Field -->
        <div class="form-group login-group__sub-title">
            {!! Form::label('bio', __('messages.bio').':' ) !!}
            {!! Form::textarea('about', null, ['class' => 'form-control user__bio login-group__input', 'rows' => 3, 'id' => 'about','placeholder'=>__('messages.bio')]) !!}
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label class="login-group__sub-title">{{ __('messages.gender').':' }}</label><span class="red">*</span>
                <div class="d-flex login-group__input align-items-center">
                    <div class="custom-control custom-radio mx-2">
                        <input type="radio" class="custom-control-input" id="male" name="gender"
                                value="{{ \App\Models\User::MALE }}" required> <label class="custom-control-label" for="male">
                            {{ __('messages.male') }}
                        </label>
                    </div>
                    <div class="custom-control custom-radio mx-2">
                        <input type="radio" class="custom-control-input" id="female" name="gender"
                               value="{{ \App\Models\User::FEMALE }}" required> <label class="custom-control-label" for="female">
                            {{ __('messages.female') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-6">
                <label class="login-group__sub-title">{{ __('messages.group.privacy').':' }}</label><span class="red">*</span>
                <div class="d-flex login-group__input align-items-center">
                    <div class="custom-control custom-radio mx-2">
                        <input type="radio" class="custom-control-input" id="privacyPrivate" name="privacy" value="0" required>
                        <label class="custom-control-label" for="privacyPrivate">
                            {{ __('messages.group.private').':' }}
                            <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top" title="All group members can send messages into the group."></i>
                        </label>
                    </div>
                    <div class="custom-control custom-radio mx-2">
                        <input type="radio" class="custom-control-input" id="privacyPublic" name="privacy" value="1"
                               required> <label
                                class="custom-control-label" for="privacyPublic">
                            {{ __('messages.group.public').':' }}
                            <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top" title="All group members can send messages into the group."></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Is Active Field -->
            <div class="form-group col-sm-6 login-group__sub-title">
                {!! Form::label('is_active', __('messages.is_active').':' ) !!}<span class="red">*</span>
                {!! Form::select('is_active', [1 => 'Active', 0 => 'In Active'], (isset($user->is_active)) ? $user->is_active : [],  ['class' => 'form-select form-control login-group__input', 'id' => 'is_active',  'required']) !!}
            </div>
            <!-- Role Field -->
            <div class="form-group col-sm-6 login-group__sub-title">
                {!! Form::label('role', __('messages.role').':' ) !!}<span class="red">*</span>
                {!! Form::select('role_id', $roles, (isset($user->role_id)) ? $user->role_id : [],  ['class' => 'form-select form-control login-group__input','placeholder' =>__('messages.placeholder.select_role'), 'id' => 'role_id', 'required']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 login-group__sub-title">
                <div class="">
                    {!! Form::label('password', __('messages.password').':' ) !!}<span class="red">*</span>
                    <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="Minimum 8 character required, White space is not allowed"></i>
                </div>
                <input type="password" name="password" class="form-control login-group__input"
                       onkeypress="return avoidSpace(event)"
                       required placeholder="{{ __('messages.password')}}">
            </div>
            <div class="form-group col-sm-6 login-group__sub-title">
                <div class="">
                    {!! Form::label('password', __('messages.confirm_password').':' ) !!}<span class="red">*</span>
                    <i class="fa fa-question-circle ms-2 question-type-open cursor-pointer"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="Minimum 8 character required, White space is not allowed"></i>
                </div>
                <input type="password" name="password_confirmation" class="form-control login-group__input"
                       onkeypress="return avoidSpace(event)" required placeholder="{{ __('messages.confirm_password')}}">
            </div>
        </div>
    </div>
    <!-- Submit Field -->
    <div class="text-start form-group col-sm-12">
        {{ Form::button(__('messages.save') , ['type'=>'submit','class' => 'btn btn-primary','id'=>'createBtnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
    </div>
</div>
