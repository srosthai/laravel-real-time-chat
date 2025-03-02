<script id="tmplAddChatUsersList" type="text/x-jsrender">
<div class="d-flex justify-content-center align-items-center">
{{if !~isArchive(deleted_at)}}
<button title="Edit" class="index__btn btn btn-ghost-success btn-sm edit-btn me-1" data-id="{{:id}}">
    <i class="cui-pencil action-icon"></i>
</button>
<button title="Archive" class="index__btn btn btn-ghost-danger btn-sm archive-btn" data-id="{{:id}}">
    <i class="fa fa-archive action-icon"></i>
</button>
{{/if}}
{{if ~isArchive(deleted_at)}}
<button title="Restore" class="index__btn btn btn-ghost-success btn-sm restore-btn me-1" data-id="{{:id}}">
    <i class="fa fa-level-up action-icon"></i>
</button>
<button title="Delete" class="index__btn btn btn-ghost-danger btn-sm delete-btn" data-id="{{:id}}">
    <i class="cui-trash action-icon"></i>
</button>
{{/if}}
<div>
</script>
