<div id="create_user_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.new_user') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="form-group col-sm-12">
                <div class="" id="validationErrorsBox"></div>
            </div>
            {!! Form::open(['id'=>'createUserForm', 'method' => 'POST']) !!}
            <div class="modal-body">
                @include('users.fields')
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

