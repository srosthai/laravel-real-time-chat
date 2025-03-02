<div id="viewReportNoteModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.reported_user') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="login-group__sub-title">
                                {{__('messages.reported_by')}}</label>:<br>
                            <span class="reported-by fw-bold w-100 d-block"></span>
                        </div>
                        <div class="col-sm-6">
                            <label class="login-group__sub-title">
                                {{__('messages.reported_to')}}</label>:<br>
                            <span class="reported-to fw-bold w-100 d-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-1">
                    <label class="login-group__sub-title">{{__('messages.notes')}}</label><br>
                    <div class="reported-user-notes fw-bold w-100 d-block"></div>
                </div>
            </div>
        </div>
    </div>
</div>
