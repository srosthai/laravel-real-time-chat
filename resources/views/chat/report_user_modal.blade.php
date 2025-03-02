<div id="reportUserModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">{{__('messages.report_user')}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    <div class="alert alert-danger" style="display: none" id="reportUserValidationErrorsBox"></div>
                </div>
                <input type="hidden" name="user_id" value="" id="reportUserId">
                <div class="col-sm-12">
                    {!! Form::label('note', __('messages.notes')) !!}<span class="red">*</span>
                    {!! Form::textarea('note', null,['class' => 'form-control', 'rows' => 5, 'id' => 'reportUserNote', 'required']) !!}
                </div>
                <div class="col-sm-12 pull-right mt-3">
                    <button type="button" class="btn btn-secondary pull-right" data-bs-dismiss="modal" aria-label="Close">
                        {{__('messages.cancel')}}
                    </button>
                    <button class="btn btn-primary me-1 pull-right" data-group-id="" id="reportUser"
                            data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                        {{__('messages.report')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
