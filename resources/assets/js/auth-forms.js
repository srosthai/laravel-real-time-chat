$(document).on('click', '#loginBtn', function (event) {
    event.preventDefault();
    if (!validateEmail() || !validatePassword()) {
        return false
    }
    $('#loginForm').submit()
});

$(document).on('click', '#registerBtn', function (event) {
    event.preventDefault();
    if (!validateName() || !validateEmail() || !validatePassword() ||
        !validatePasswordConfirmation() || !validateMatchPasswords()) {
        return false
    }
    $('#registerForm').submit()
});

$(document).on('click', '#forgetPasswordBtn', function (event) {
    event.preventDefault();
    if (!validateEmail()) {
        return false
    }
    $('#forgetPasswordForm').submit()
});

$(document).on('click', '#resetPasswordBtn', function (event) {
    event.preventDefault();
    if (!validateEmail() || !validatePassword() ||
        !validatePasswordConfirmation() || !validateMatchPasswords()) {
        return false
    }
    $('#resetPasswordForm').submit()
});

let form = $('form');
form.on('keypress', function (e) {
    if (e.keyCode === 13) {
        let loginBtn = form.find('#loginBtn');
        let registerBtn = form.find('#registerBtn');
        let forgetPasswordBtn = form.find('#forgetPasswordBtn');
        let resetPasswordBtn = form.find('#resetPasswordBtn');
        if (loginBtn.length > 0) {
            loginBtn.trigger('click')
        } else if (registerBtn.length > 0) {
            registerBtn.trigger('click')
        } else if (forgetPasswordBtn.length > 0) {
            forgetPasswordBtn.trigger('click')
        } else if (resetPasswordBtn.length > 0) {
            resetPasswordBtn.trigger('click')
        }
    }
});

//validations
let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/
window.validateName = function () {
    let name = $('#name').val();
    if (name === '') {
        displayToastr('Error', 'error', 'The name field is required.');
        return false
    }
    return true
};

window.validatePasswordConfirmation = function () {
    let passwordConfirmation = $('#password_confirmation').val();
    if (passwordConfirmation === '') {
        displayToastr('Error', 'error',
            'The password confirmation field is required.');
        return false
    }
    return true
};

window.validateMatchPasswords = function () {
    let passwordConfirmation = $('#password_confirmation').val();
    let password = $('#password').val();
    if (passwordConfirmation !== password) {
        displayToastr('Error', 'error',
            'The password and password confirmation did not match.');
        return false
    }
    return true
};

window.validatePassword = function () {
    let password = $('#password').val();
    if (password === '') {
        displayToastr('Error', 'error', 'The password field is required.');
        return false
    }
    return true
};

window.validateEmail = function () {
    let email = $('#email').val();
    if (email === '') {
        displayToastr('Error', 'error', 'The email field is required.');
        return false
    } else if (!validateEmailFormat(email)) {
        displayToastr('Error', 'error', 'Please enter valid email.');
        return false
    }
    return true
};

window.validateEmailFormat = function (email) {
    return emailReg.test(email)
};

window.avoidSpace = function(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k === 32) {
        event.stopPropagation();
        return false;
    }
}
