$(function () {
    $.validator.addMethod("emailValidation", function (value, element) {
        return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    });
    $.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    });

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-ZÄäÜüÖö\u0600-\u06FF][\sa-zA-ZÄäÜüÖö\u0600-\u06FF]*/);
    });

    $.validator.addMethod("alphaNumeric", function (value, element) {
        return this.optional(element) || value.match(/^[a-zA-ZÄäÜüÖö0-9\u0600-\u06FF][\sa-zA-ZÄäÜüÖö0-9\u0600-\u06FF]*/);
    });

    $.validator.addMethod("noDigits", function (value, element) {
        return this.optional(element) || value != value.match(/^[0-9]*/);
    }, 'Only Numbers Not Allowed');

    jQuery.validator.addMethod("checkFullAddress", function (value, element, params) {
        var city = $(element).parents('.search-address').find('.city').val();
        var country = $(element).parents('.search-address').find('.country').val();

        if (city == '' || country == '') {
            return false;
        }
        return true;
    });

    $(".SignupForm").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorElement: 'span',
        errorClass: 'error',
        highlight: function (element, errorClass) {
            if($(element).next().next('span').length){
                $(element).next().next('span').remove();
                $(element).parent().removeClass('has-error');
            }
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        // Different components require proper error label placement
        errorPlacement: function (error, element) {
            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent().parent().parent());
                }
                else {
                    error.appendTo(element.parent().parent().parent());
                }
            }
            // Unstyled checkboxes, radios
            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo(element.parent().parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function (label) {
            label.remove();
            $(".form-control-select2").change(function () {
                if ($(this).val() != "") {
                    $(this).valid();
                }
            });
        },
        rules: {
            name: {
                required: true,
                alpha: true,
                // alphaNumeric:true,
                noDigits:true,
                minlength:3,
                maxlength:50,
            },
            email: {
                required: true,
                emailValidation: true,
                email:true,
                minlength: 3,
                maxlength: 50,
                remote: {
                    type: "POST",
                    url: base_url + 'email-exists'
                },
                normalizer: function (value) {
                    return $.trim(value);
                }
            },
            password: {
                required: true,
                noSpace: true,
                minlength: 6,
            },
            password_confirmation: {
                required: true,
                noSpace: true,
                equalTo: "#password"
            },
        },
        debug:true,
        messages: {
            name: {
                required: "Please enter name",
                alpha: "The name may only contain Letters and Spaces",
                minlength: "At Least {0} characters required",
                maxlength: "Maximum {0} characters are allowed",
            },
            email: {
                required: "Please enter your e-mail address.",
                emailValidation: "Please enter a valid email address" ,
                email: "Please enter a valid email address" ,
                remote: "his email address is already exists",
                minlength: "At Least {0} characters required",
                maxlength: "Maximum {0} characters are allowed",
            },
            password: {
                required: "Please enter a password",
                noSpace: "Space are not allowed",
                minlength: "At Least {0} characters required"
            },
            password_confirmation: {
                required: "Please enter a confirm password",
                noSpace: "Space are not allowed",
                equalTo: "Password and Confirm Password does not match"
            },
        },
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr('disabled', 'disabled');
            form.submit();
        }
    });

});
