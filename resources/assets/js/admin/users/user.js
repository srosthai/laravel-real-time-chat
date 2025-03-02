$(document).ready(function () {
    $('#filter_user').select2({
        minimumResultsForSearch: -1,
    });
    $('#privacy_filter').select2({
        minimumResultsForSearch: -1,
    });

    let tbl = $('#users_table').DataTable({
        processing: true,
        serverSide: true,
        'bStateSave': true,
        'order': [[1, 'asc']],
        ajax: {
            url: route('users.index'),
            data: function (data) { 
                data.filter_user = $('#filter_user').find('option:selected').val();
                data.privacy_filter = $('#privacy_filter').find('option:selected').val();
            },
        },
        columnDefs: [
            {
                'targets': [0],
            },
            {
                'targets': [3, 4, 5, 6],
                'orderable': false,
                'className': 'text-center',
                'width': '10%',
            },
        ],
        columns: [
            {
                data: function (row) {
                    return `<div class="d-flex align-items-center"> <div class="symbol symbol-circle symbol-50px overflow-hidden mr-3"> <a href="javascript:void(0)"> <div> <img src="${row.photo_url}" alt="User Image" class="user-avatar-img"> </div> </a> </div> <div class="d-flex flex-column"> <a href="javascript:void(0)" class="mb-1 user-name-data">${htmlSpecialCharsDecode(row.name)}</a> <span class="user-email-data">${row.email}</span> </div> </div> `
                }, name: 'name',
            },
            {
                data: function (data) {
                    let role_name = getRoleName(data.roles);
                    return htmlSpecialCharsDecode(role_name);
                },
                name: 'email',
            },
            {
                data: function (data) {
                    return (data.privacy) ? '<span class="public-badge py-1 px-2">Public</span>' : '<span class="private-badge py-1 px-2">Private</span>';
                },
                name: 'privacy',
                'searchable': false,
            },
            {
                data: function (row) {
                    let checked = row.email_verified_at == null ? '' : 'checked disabled';
                    return ' <label class="switch switch-label switch-outline-primary-alt align-middle">' +
                        '<input name="email_verified" data-id="' + row.id +
                        '" class="switch-input email-verified" type="checkbox" ' +
                        checked + '>' +
                        '<span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>' +
                        '</label>';
                }, name: 'id',
            },
            {
                data: function (row) {
                    let checked = row.is_active == 0 ? '' : 'checked';
                    return ' <label class="switch switch-label switch-outline-primary-alt align-middle">' +
                        '<input name="is_active" data-id="' + row.id +
                        '" class="switch-input is-active" type="checkbox" value="1" ' +
                        checked + '>' +
                        '<span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>' +
                        '</label>';
                }, name: 'id',
            },
            {
                data: function (row) {
                    return `<a title="" href="${route('user-impersonate-login',row.id)}" class="btn btn-primary btn-sm">
                               Impersonate
                            </a>`;
                }, name: 'id',
            },
            {
                data: function (row) {
                    let helpers = {
                        isArchive: isArchive,
                    };
                    let template = $.templates('#tmplAddChatUsersList');
                    return template.render(row, helpers);
                }, name: 'id',
            },
        ],
        drawCallback: function () {
            this.api().state.clear();
        },
        'fnInitComplete': function () {
            $('#filter_user').change(function () {
                tbl.ajax.reload()
            });
            $('#privacy_filter').change(function () {
                tbl.ajax.reload()
            });
        },
    });

    window.isArchive = function(deletedAt) {
        return (deletedAt != null) ? 1 : 0;
    }

    window.getRoleName = function(roles) {
        let roleName = '';
        $.each(roles, (index, val) => {
            roleName = val.name;
            return false;
        });
        return roleName;
    }

    $('#createUserForm').on('submit', function (event) {
        event.preventDefault();
        let loadingButton = jQuery(this).find('#createBtnSave');
        loadingButton.button('loading');
        $.ajax({
            url: route('users.store'),
            type: 'post',
            data: new FormData($(this)[0]),
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success) {
                    displayToastr('Success', 'success', result.message);
                    $('#create_user_modal').modal('hide');
                    $('#users_table').DataTable().ajax.reload(null, false);
                }
            },
            error: function (result) {
                displayToastr('Error', 'error', result.responseJSON.message);
            },
            complete: function () {
                loadingButton.button('reset');
            },
        });
    });

    $('#editUserForm').on('submit', function (event) {
        event.preventDefault();
        let loadingButton = jQuery(this).find('#editBtnSave');
        loadingButton.button('loading');
        let id = $('#edit_user_id').val();
        $.ajax({
            url: route('user.update',id),
            type: 'post',
            data: new FormData($(this)[0]),
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success) {
                    displayToastr('Success', 'success', result.message);
                    $('#edit_user_modal').modal('hide');
                    $('#users_table').DataTable().ajax.reload(null, false);
                }
            },
            error: function (result) {
                displayToastr('Error', 'error', result.responseJSON.message);
            },
            complete: function () {
                loadingButton.button('reset');
            },
        });
    });

    $(document).on('click', '.edit-btn', function () {
        let userId = $(this).data('id');
        renderData(route('users.edit',userId));
    });

    window.renderData = function (url) {
        $.ajax({
            url: url,
            type: 'GET',
            // cache: false,
            success: function (result) {
                if (result.success) {
                    let user = result.data.user;
                    $('#edit_user_id').val(user.id);
                    $('#edit_name').val(htmlSpecialCharsDecode(user.name));
                    $('#edit_email').val(user.email);
                    $('#edit_phone').val(user.phone);
                    $('#edit_is_active').val(user.is_active);
                    $('#edit_role_id').val(user.role_id);
                    $('#edit_upload-photo-img').attr('src', user.photo_url);
                    $('#edit_about').val(htmlSpecialCharsDecode(user.about));
                    $('#edit_user_modal').modal('show');
                    if (user.gender == 1) {
                        $('#edit_male').prop('checked', true);
                    }
                    if (user.gender == 2) {
                        $('#edit_female').prop('checked', true);
                    }

                    if (user.privacy == 1) {
                        $('#editPrivacyPublic').prop('checked', true);
                    } else {
                        $('#editPrivacyPrivate').prop('checked', true);
                    }
                }
            },
            error: function (error) {
                displayToastr('Error', 'error', error.responseJSON.message);
            },
        });
    };

    const swalDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger mr-2 btn-lg',
            cancelButton: 'btn btn-secondary btn-lg',
        },
        buttonsStyling: false,
    });

    // open delete confirmation model
    $(document).on('click', '.delete-btn', function (event) {
        let userId = $(this).data('id');
        deleteItem(route('users.destroy',userId), '#users_table', Lang.get('messages.placeholder.user'));
    });

    function deleteItem (url, tableId, header, callFunction = null) {
        swalDelete.fire({
            title: Lang.get('messages.placeholder.are_you_sure'),
            html: Lang.get('messages.placeholder.want_to_delete_this') +'"'+ header +'"'+ ' ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            input: 'text',
            inputPlaceholder: Lang.get('messages.placeholder.write_delete_user'),
            inputValidator: (value) => {
                if (value !== "delete") {
                    return Lang.get('messages.placeholder.need_to_write_delete')
                }
            }
        }).then((result) => {
            if (result.value) {
                deleteItemAjax(url, tableId, header, callFunction = null);
            }
        });
    }

    $(document).on('click', '.archive-btn', function () {
        let userId = $(this).data('id');
        archiveItem(route('archive-user',userId), '#users_table', Lang.get('messages.placeholder.user'));
    });

    function archiveItem (url, tableId, header, callFunction = null) {
        swalDelete.fire({
            title: Lang.get('messages.placeholder.are_you_sure'),
            input: 'text',
            inputPlaceholder: Lang.get('messages.placeholder.confirm_archive'),
            html: Lang.get('messages.placeholder.want_to_archive')+' "'+ header +'" '+ Lang.get('messages.placeholder.after_archive'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Archive',
            inputValidator: (value) => {
                if (value !== "archive") {
                    return Lang.get('messages.placeholder.you_need_to')
                }
            }
        }).then((result) => {
            if (result.value) {
                archiveItemAjax(url, tableId, header, callFunction = null);
            }
        });
    }

    window.archiveItemAjax = function (url, tableId, header, callFunction = null) {
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function (obj) {
                if (obj.success) {
                    $(tableId).DataTable().ajax.reload(null, false);
                }
                displayToastr('Success', 'success',obj.message);
            },
            error: function (data) {
                displayToastr('Error', 'error', data.responseJSON.message);
            },
        });
    };

    $(document).on('click', '.restore-btn', function (event) {
        let userId = $(this).data('id');
        restoreItem(route('user.restore-user'), '#users_table', Lang.get('messages.placeholder.user'), userId);
    });

    function restoreItem (url, tableId, header, userId) {
        swal.fire({
            title: Lang.get('messages.placeholder.are_you_sure'),
            html: Lang.get('messages.placeholder.want_to_restore') +'"'+ header + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Restore',
        }).then((result) => {
            if (result.value) {
                restoreItemAjax(url, tableId, header, userId);
            }
        });
    }

    window.restoreItemAjax = function (url, tableId, header, userId) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {'id': userId},
            dataType: 'json',
            success: function (obj) {
                if (obj.success) {
                    $(tableId).DataTable().ajax.reload(null, false);
                }
                displayToastr('Success', 'success',
                    header + ' has been restored.');
            },
            error: function (data) {
                displayToastr('Error', 'error', data.responseJSON.message);
            },
        });
    };

    $('#create_user_modal').on('hidden.bs.modal', function () {
        resetModalForm('#createUserForm', '#validationErrorsBox');
        $('#upload-photo-img').attr('src', defaultImageAvatar);
    });
    $('#edit_user_modal').on('hidden.bs.modal', function () {
        resetModalForm('#editUserForm', '#editValidationErrorsBox');
    });

    function resetModalForm (formId, validationBox) {
        $(formId)[0].reset();
        $(validationBox).hide();
    }

    function printErrorMessage (selector, errorMessage) {
        $(selector).show().html('');
        $(selector).append('<div>' + errorMessage + '</div>');
    }

    // listen user activation deactivation change event
    $(document).on('change', '.is-active', function (event) {
        const userId = $(event.currentTarget).data('id');
        activeDeActiveUser(userId);
    });

    // activate de-activate user
    window.activeDeActiveUser = function (id) {
        $.ajax({
            url: route('active-de-active-user',id),
            method: 'post',
            cache: false,
            success: function (result) {
                if (result.success) {
                    displayToastr('Success', 'success', result.message);
                    $('#users_table').DataTable().ajax.reload(null, false);
                }
            },
        });
    };

    // Email verified
    $(document).on('change', '.email-verified', function (event) {
        const userId = $(event.currentTarget).data('id');
        $.ajax({
            url: route('user.email-verified',userId),
            method: 'post',
            cache: false,
            success: function (result) {
                if (result.success) {
                    displayToastr('Success', 'success', result.message);
                    $('#users_table').DataTable().ajax.reload(null, false);
                }
            },
        });
    });

    window.validatePasswordConfirmation = function () {
        let passwordConfirmation = $('#confirm_password').val();
        if (passwordConfirmation === '') {
            displayToastr('Error', 'error',
                'The password confirmation field is required.');
            return false;
        }
        return true;
    };

    window.validateMatchPasswords = function () {
        let passwordConfirmation = $('#confirm_password').val();
        let password = $('#password').val();
        if (passwordConfirmation !== password) {
            displayToastr('Error', 'error',
                'The password and password confirmation did not match.');
            return false;
        }
        return true;
    };

    window.validatePassword = function () {
        let password = $('#password').val();
        if (password === '') {
            displayToastr('Error', 'error', 'The password field is required.');
            return false;
        }
        return true;
    };
});
