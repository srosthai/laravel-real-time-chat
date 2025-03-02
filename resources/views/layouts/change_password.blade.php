<div id="changePasswordModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.change_password') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            {!! Form::open(['id'=>'changePasswordForm']) !!}
            @csrf
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    <div class="alert alert-danger" style="display: none" id="validationErrorsBox"></div>
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('password', __('messages.new_password').':', ['class' => 'login-group__sub-title']) !!}<span class="red">*</span>
                    {!! Form::password('password', ['class' => 'form-control login-group__input', 'required','placeholder'=>__('messages.new_password')]) !!}
                </div>
                <div class="form-group col-sm-12 mb-4">
                    {!! Form::label('password_confirmation', __('messages.confirm_password').':', ['class' => 'login-group__sub-title']) !!}<span
                            class="red">*</span>
                    {!! Form::password('password_confirmation', ['class' => 'form-control login-group__input', 'required','placeholder'=> __('messages.confirm_password')]) !!}
                </div>
                <div class="text-start form-group col-sm-12 mb-2">
                    {!! Form::button(__('messages.save'), ['type'=>'submit','class' => 'btn btn-primary','id'=>'btnCreateSave','data-loading-text'=>"<i class='fa fa-refresh fa-spin'></i> " .__('messages.processing')]) !!}
                    <button type="button" id="cancelPasswordModalBtn" class="btn btn-secondary close_create_role ms-1"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
