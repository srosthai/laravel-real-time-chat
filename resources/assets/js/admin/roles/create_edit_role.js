
$('#createRoleForm').on('submit', function (event) {
    event.preventDefault();
    let name = $('#role_name').val();
    let emptyName = name.trim().replace(/ \r\n\t/g, '') === '';
    
    if (emptyName) {
        displayToastr('Error', 'error', 'Name field is not contain only white space');
        return 
    }
    
    let loadingButton = jQuery(this).find('#btnCreateRole');
    loadingButton.button('loading');

    $('#createRoleForm')[0].submit();

    return true;
});

$('#editRoleForm').on('submit', function (event) {
    event.preventDefault();
    let editName = $('#edit_role_name').val();
    let emptyEditName = editName.trim().replace(/ \r\n\t/g, '') === '';

    if (emptyEditName) {
        displayToastr('Error', 'error', 'Name field is not contain only white space');
        return
    }
    
    let loadingButton = jQuery(this).find('#btnEditSave');
    loadingButton.button('loading');

    $('#editRoleForm')[0].submit();

    return true;
});
