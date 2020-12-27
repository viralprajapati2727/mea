var d = new Date();
var Y = d.getFullYear();
var M = parseInt(d.getMonth());
var D = parseInt(d.getDate()) + 1;
$(".birthdate").datetimepicker({
    ignoreReadonly: true,
    useCurrent: false,
    format: 'MM/DD/YYYY',
    maxDate: new Date(Y, M, D),
    disabledDates: [
        new Date(Y, M, D)
    ],
});


$(document).ready(function(){
    $.validator.addMethod("onlyDigitNotAllow", function (value, element, param) {
        return (/^[0-9]*$/gm.test(value)) ? false : true;
    }, "Only Digits are not allowed");

    $.validator.addMethod("NotValidName", function (value, element, param) {
        return (/^[0-9~`!@#$%^*&()_={}[\]:;,.<>+\/?-]*$/gm.test(value)) ? false : true;
    }, "Please enter valid name");

    $.validator.addMethod("onlySpecialCharactersNotAllow", function (value, element, param) {
        return (/^[~`!@#$%^*&()_={}[\]:;,.<>+\/?-]*$/gm.test(value)) ? false : true;
    }, "Only Special characters are not allowed");

    $.validator.addMethod("alpha", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z][\sa-zA-Z]*/);
    });

    $.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "Space are not allowed");

    $.validator.addMethod("emailValidation", function (value, element) {
        return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,8}$/i.test(value);
    });
    $.validator.addMethod("nameValidation", function (value, element) {
        return this.optional(element) || /^[+a-zA-Z0-9._-]$/i.test(value);
    });

    var validator = $('.appointment_form').validate({
        ignore: 'input[type=hidden], .select2-search__field' ,
        errorElement: 'span',
        errorClass: 'error',
        highlight: function (element, errorClass) {
            $(element).parent('div').find('.server-error').remove();
            // $(element).addClass(errorClass);
            $(element).addClass('error-border');
            $(element).next().find('.select2-selection--single').addClass('error-border');
            $(element).next().find('button').addClass('error-border');
        },
        unhighlight: function (element, errorClass) {
            // $(element).removeClass(errorClass);
            $(element).removeClass('error-border');
            $(element).next().find('.select2-selection--single').removeClass('error-border');
            $(element).next().find('button').removeClass('error-border');
        },
        errorPlacement: function (error, element) {
            if (element.parents('div').hasClass('account-img-content')) {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parents('div').hasClass('custom-start')) {
                error.appendTo(element.parent().parent());
            } else if (element.parents('div').hasClass('form-group')) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 50,
                normalizer: function (value) { return $.trim(value); },
                onlySpecialCharactersNotAllow: true,
                NotValidName: true,
                onlyDigitNotAllow: true,
            },
            email: {
                required: true,
                emailValidation: true,
                maxlength: 255,
                noSpace: true,
            },
            date: {
                required: true,
            },
            time: {
                required: true,
            },
            description: {
                required: true,
                minlength: 20,
                maxlength: 800,
            },
        },
        debug:true,
        messages: {
            name: {
                required: "Please enter your name",
                minlength: jQuery.validator.format("At least {0} characters are required"),
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
                NotValidName: "Please enter valid name",
            },
            email: {
                required: "Please enter your email",
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
                emailValidation: "Please enter valid email address",
            },
            date: {
                required: "Please select appointment date",
            },
            time: {
                required: "Please enter time intervel",
            },
            description: {
                required: "Please enter appointment details",
                minlength: jQuery.validator.format("At least {0} characters are required"),
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
            },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.about.updateElement();
            // $(form).find('button[type="submit"]').attr('disabled', 'disabled');
            // form.submit();
            $('.appointment_form').ajaxSubmit(
                {
                    beforeSubmit:  showRequest_pro_profile,  // pre-submit callback
                    success:       showResponse_pro_profile,  // post-submit callback
                    error: errorResponse_pro_profile,
                }
            );
        }
    });



    $('.questions .answer').each(function () {
        $(this).rules('add', {
            required: true,
            minlength: 3,
            maxlength : 100,
            messages: {
                required:  "Please enter answer",
                minlength: jQuery.validator.format("At least {0} characters are required"),
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });

    validateExtraField();
    validateEducationExtraField();

})    

// pre-submit callback
function showRequest_pro_profile(formData, jqForm, options) {
    jqForm.find('.submit-btn').attr("disabled",true);
    jqForm.find('.submit-icon').removeClass('flaticon-save');
    jqForm.find('.submit-icon').addClass('fa fa-lg fa-refresh fa-spin');
    jqForm.find('.submit-icon').removeClass('flaticon-save-file-option');
}

// post-submit callback
function showResponse_pro_profile(responseText, statusText, xhr, jqForm)  {

    if(responseText.status == '1') {
        if(typeof(responseText.redirect) != "undefined" && responseText.redirect !== null ){
            location.href = responseText.redirect;
        }else{
            location.href = base_url;
        }
    }else{
        jqForm.find('.submit-btn').attr("disabled",false);
        jqForm.find('.submit-icon').addClass('flaticon-save');
        jqForm.find('.submit-icon').removeClass('fa fa-lg fa-refresh fa-spin');
        jqForm.find('.submit-icon').addClass('flaticon-save-file-option');
        jqForm.find('.validation-error-label').remove();

        if(Object.keys(responseText.errors).length > 0){
            $.each(responseText.errors, function(idx, obj) {
                if(idx.indexOf('[]') != -1 || idx == 'gender'){
                    idx = idx.replace(/\[]/g, "");
                    obj[0] = obj[0].replace(/\[]/g, "");
                    if(idx == 'gallery-photo-add'){
                        $(".gallary-validation-server").html(obj[0]).show();
                    }else{
                        $("#"+idx).addClass('error-border')
                        $("#"+idx).parent('div').append('<span id="' + idx + '-error" class="validation-error-label server-error">' + obj[0] + '</span></div>')
                    }
                }else {
                    $("input[name='" + idx + "']").addClass('error-border')
                    $("input[name='" + idx + "']").parent('div').append('<span id="' + idx + '-error" class="validation-error-label server-error">' + obj[0] + '</span></div>')
                }
            });
        }else{
            $('.flash-messages').html('<div class="d-block error-message custom-error">\n' +
                '<div class="alert alert-danger alert-block">\n' +
                '\t<button type="button" class="close" data-dismiss="alert">×</button>\n' +
                '    <span>'+responseText.message+'</span>\n' +
                '</div>\n' +
                '</div>');
            window.scrollTo(0,0);
        }
        jqForm.find('.server-error:first').parent('div').find('input').focus()
    }

}

function errorResponse_pro_profile(xhr, textStatus, errorThrown, jqForm) {
    console.log(errorThrown);
    // location.href = base_url;
    jqForm.find('.submit-btn').attr("disabled",false);
    jqForm.find('.submit-btn').addClass('flaticon-save');
    jqForm.find('.submit-btn').removeClass('fa fa-lg fa-refresh fa-spin');
}