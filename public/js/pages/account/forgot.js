$(function () {
    $.validator.addMethod("emailValidation", function (value, element) {
        return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    });
    
    $("#ForgotpasswordForm").validate({   
        ignore: 'input[type=hidden]', // ignore hidden fields
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
        
        rules: {
            email: {
            required: true,
            email: true,
            remote: {
                    type: "POST",
                    url: base_url+"check-email",
                }
            }
            },
        messages: {
            email: {
                    required: "Please enter your e-mail address.",
                    emailValidation: "Please enter a valid email address",
                    remote: "We can't find user with that e-mail address",
            }
        },
    
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr('disabled', 'disabled');
            console.log('submit');
            
            form.submit();
        },
            
    });

});