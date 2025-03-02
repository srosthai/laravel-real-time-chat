<div id="setCustomStatusModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti-user"></i>{{__('messages.partials.set_a_status')}}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body mt-2">
                <div class="mb-2 col-sm-12">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend user-status-emoji profile-emoji">
                            <input type="text" class="form-control" id="userStatusEmoji">
                        </div>
                        <input type="text" class="form-control login-group__input" id="userStatus"
                               placeholder="{{ __('messages.whats_your_status') }}...">
                    </div>
                    <div class="col-sm-12 my-2 p-0 text-start">
                        {{ Form::button(__('messages.save') , ['type'=>'button','class' => 'btn btn-primary','id'=>'setUserStatus', 'data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
                        {{ Form::button(__('messages.partials.clear_status') , ['type'=>'button','class' => 'btn btn-secondary','id'=>'clearUserStatus', 'data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> " .__('messages.processing')]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
