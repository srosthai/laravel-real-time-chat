<div id="copyImageModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">{{__('messages.chats.upload_image')}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div id="imageCanvas" class="image-canvas"></div>
                <button type="button" class="btn btn-secondary me-1 pull-right mt-3" data-bs-dismiss="modal"
                        aria-label="Close">
                    {{__('messages.chats.cancel')}}
                </button>
                <button class="btn btn-success me-1 pull-right mt-3" data-group-id="" id="sendImages"
                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                    {{__('messages.chats.send')}}
                </button>
            </div>
        </div>
    </div>
</div>
