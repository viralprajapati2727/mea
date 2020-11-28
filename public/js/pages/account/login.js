$(function () {
    $.validator.addMethod("emailValidation", function (value, element) {
        return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    });
    // Setup validation
    $("#SigninForm").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        successClass: 'validation-valid-label',
        errorElement: 'span',
        errorClass: 'error',
        highlight: function (element, errorClass) {
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
                    error.appendTo(element.parent().parent().parent().parent().parent());
                }
            }
            else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function (label) {
            label.remove();
        },
        rules: {
            email: {
                required: true,
                emailValidation: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter your e-mail address.",
                emailValidation: "Please enter a valid email address",
            },
            password: {
                required: "Please enter a password",
            },
        },
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr('disabled', 'disabled');
            form.submit();
        }
    });
});