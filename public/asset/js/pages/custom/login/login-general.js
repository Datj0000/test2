"use strict";

// Class Definition
var KTLogin = function () {
    var _login;

    var _showForm = function (form) {
        var cls = 'login-' + form + '-on';
        var form = 'kt_login_' + form + '_form';

        _login.removeClass('login-forgot-on');
        _login.removeClass('login-signin-on');
        _login.removeClass('login-signup-on');

        _login.addClass(cls);

        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
    }
    function _send_token(email){
        $.ajax({
            url: 'send-token',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
            },
            data: {
                email: email,
            }
        })
    }
    const strongPassword = function () {
        return {
            validate: function (input) {
                const value = input.value;
                if (value === '') {
                    return {
                        valid: true,
                    };
                }

                if (value.length < 8) {
                    return {
                        valid: false,
                    };
                }

                if (value === value.toLowerCase()) {
                    return {
                        valid: false,
                    };
                }

                if (value === value.toUpperCase()) {
                    return {
                        valid: false,
                    };
                }

                if (value.search(/[0-9]/) < 0) {
                    return {
                        valid: false,
                    };
                }

                return {
                    valid: true,
                };
            },
        };
    };
    var _handleSignInForm = function () {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('kt_login_signin_form'),
            {
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n email'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n m???t kh???u'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        $('#kt_login_signin_submit').on('click', function (e) {
            e.preventDefault();
            var email = $('#email').val();
            var password = $('#password').val();
            validation.validate().then(function (status) {
                if (status != 'Valid') {
                    swal.fire({
                        text: "Xin l???i, c?? v??? nh?? ???? ph??t hi???n th???y m???t s??? l???i, vui l??ng th??? l???i .",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "?????ng ??!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                } else {
                    $.ajax({
                        url: 'login',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                        data: {
                            email: email,
                            password: password,
                        },
                        success: function(data) {
                            if (data == 0) {
                                Swal.fire("", "Sai t??i kho???n ho???c m???t kh???u!", "warning");
                            } else {
                                window.location = 'dashboard';
                            }
                        }
                    })
                }
            });
        });

        // Handle forgot button
        $('#kt_login_forgot').on('click', function (e) {
            e.preventDefault();
            _showForm('forgot');
        });

        // Handle signup
        $('#kt_login_signup').on('click', function (e) {
            e.preventDefault();
            _showForm('signup');
        });
    }
    var _handleSignUpForm = function (e) {
        var validation;
        var form = KTUtil.getById('kt_login_signup_form');
        FormValidation.validators.checkPassword = strongPassword;
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            form,
            {
                fields: {
                    token: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n m?? token'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n m???t kh???u'
                            },
                            checkPassword: {
                                message: 'M???t kh???u ??t nh???t 8 k?? t??? g???m c??? s??? v?? ch??? vi???t hoa, vi???t th?????ng'
                            },
                        }
                    },
                    cpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n m???t kh???u'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'Hai m???t kh???u vui l??ng tr??ng nhau'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );
        $('#kt_login_signup_submit').on('click', function (e) {
            e.preventDefault();
            var email = $('#email_forgot').val();
            var token = $('#token').val();
            var password = $('#password').val();
            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    $.ajax({
                        url: 'reset-pass',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                        data: {
                            email: email,
                            token: token,
                            password: password
                        },
                        success: function (data) {
                            if (data == 0) {
                                Swal.fire("", "M?? c???a b???n ???? h???t h???n!", "warning");
                            }
                            else if (data == 1) {
                                Swal.fire("", "?????i m???t kh???u th??nh c??ng!", "success");
                                _showForm('signin');
                            }
                        }
                    })
                } else {
                    swal.fire({
                        text: "Xin l???i, c?? v??? nh?? ???? ph??t hi???n th???y m???t s??? l???i, vui l??ng th??? l???i .",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "?????ng ??!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                }
            });
        });

        // Handle cancel button
        $('#kt_login_signup_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }
    var _handleForgotForm = function (e) {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            KTUtil.getById('kt_login_forgot_form'),
            {
                fields: {
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ??i???n email'
                            },
                            emailAddress: {
                                message: 'Email n??y kh??ng h???p l???'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        // Handle submit button
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();
            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    var email = $('#email_forgot').val();
                    $.ajax({
                        url: 'recover-pass',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                        data: {
                            email: email,
                        },
                        success: function (data) {
                            if (data == 0) {
                                Swal.fire("", "Email n??y ch??a ????ng k?? t??i kho???n!", "warning");
                            }
                            else if (data == 1) {
                                Swal.fire("", "Vui l??ng ki???m tra email ????? l???y l???i m???t kh???u!", "success");
                                _showForm('signup');
                                _send_token(email);
                            }
                        }
                    })
                } else {
                    swal.fire({
                        text: "Xin l???i, c?? v??? nh?? ???? ph??t hi???n th???y m???t s??? l???i, vui l??ng th??? l???i .",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "?????ng ??!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                }
            });
        });
        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('signin');
        });
    }

    // Public Functions
    return {
        // public functions
        init: function () {
            _login = $('#kt_login');

            _handleSignInForm();
            _handleSignUpForm();
            _handleForgotForm();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function () {
    KTLogin.init();
});
