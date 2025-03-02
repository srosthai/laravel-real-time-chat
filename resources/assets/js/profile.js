$('#editProfileForm').on('submit', function (event) {
    if (!validateName() || !validateEmail() || !validatePhone()) {
        return false;
    }
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnEditSave');
    loadingButton.button('loading');
    $.ajax({
        url: route('update.profile'),
        type: 'post',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displayToastr('Success', 'success', result.message);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        },
        error: function (result) {
            displayToastr('Error', 'error', result.responseJSON.message);
            setTimeout(function () {
                location.reload();
            }, 2000);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

$(":checkbox:not('.not-checkbox')").iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square',
    increaseArea: '20%', // optional
});

$('#upload-photo').on('change', function () {
    readURL(this);
});

let on = $('#btnCancelEdit').on('click', function () {
    $('#editProfileForm').trigger('reset');
});

// profile js
function readURL (input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#upload-photo-img').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

window.printErrorMessage = function (selector, errorResult) {
    $(selector).show().html('');
    $(selector).text(errorResult.responseJSON.message);
};

//validations
let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

window.validateName = function () {
    let name = $('#user-name').val();
    if (name === '') {
        displayToastr('Error', 'error', 'The name field is required.');
        return false;
    }
    return true;
};

window.validateEmail = function () {
    let email = $('#email').val();
    if (email === '') {
        displayToastr('Error', 'error', 'The email field is required.');
        return false;
    } else if (!validateEmailFormat(email)) {
        displayToastr('Error', 'error', 'Please enter valid email.');
        return false;
    }
    return true;
};

window.validateEmailFormat = function (email) {
    return emailReg.test(email);
};

window.validatePhone = function () {
    let phone = $('#phone').val();
    if (phone !== '' && phone.length !== 10) {
        displayToastr('Error', 'error',
            'The phone number must be 10 digits long.');
        return false;
    }
    return true;
};

$('#phone').on('keypress keyup blur', function (e) {
    $(this).val($(this).val().replace(/[^\d].+/, ''));
    if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
        return false;
    }
});

const swalDelete = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-danger mr-2 btn-lg',
        cancelButton: 'btn btn-secondary btn-lg',
    },
    buttonsStyling: false,
});

$('.remove-profile-img').on('click', function (e) {
    e.preventDefault();

    swalDelete.fire({
        title: 'Are you sure?',
        html: 'Your profile image removed by clicking on YES.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'DELETE',
                url: route('remove-profile-image'),
                success: function (result) {
                     displayToastr('Success', 'success', result.message);
                     setTimeout(function () {
                     location.reload();
                     }, 2000);
                },
                error: function (error) {
                     displayToastr('Error', 'error', error.message);
                },
            });
        }
    });
});

$(document).on('click','.changeLanguage', function () {
    let languageName = $(this).data('prefix-value');
    $.ajax({
        type: 'POST',
        url: route('update-language'),
        data: { languageName: languageName },
        success: function () {
            location.reload();
        },
    });
});

$('#changePasswordModal').on('hidden.bs.modal', function () {
    resetModalForm('#changePasswordForm', '#validationErrorsBox');
});
