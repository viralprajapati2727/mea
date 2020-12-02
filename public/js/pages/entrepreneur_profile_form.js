var exp_array = [];
var edu_array = [];
 //file upload button Jquery
 $("#profile-photo-add-btn").on('click',function(e){
    // e.preventDefault();
    $("#profile-photo-add").get(0).click();
});

$('#profile-photo-add').on('change',function () {
    $('input:file').valid();
});
$(".profile_image").on('change',function(){
    readURL(this);
});

var d = new Date();
var Y = d.getFullYear();
var M = parseInt(d.getMonth());
var D = parseInt(d.getDate()) + 1;
$(".birthdate").datetimepicker({
    ignoreReadonly: true,
    useCurrent: false,
    format: 'DD/MM/YYYY',
    maxDate: new Date(Y, M, D),
    disabledDates: [
        new Date(Y, M, D)
    ],
});

CKEDITOR.replace('about', {
    height: '200px',
    removeButtons: 'Subscript,Superscript,Image',
    toolbarGroups: [
        { name: 'styles' },
        { name: 'editing', groups: ['find', 'selection'] },
        { name: 'basicstyles', groups: ['basicstyles'] },
        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'colors' },
        { name: 'tools' },
        { name: 'others' },
        { name: 'document', groups: ['mode', 'document', 'doctools'] }
    ],
    wordcount: {
        // Whether or not you want to show the Paragraphs Count
        showParagraphs: false,

        // Whether or not you want to show the Word Count
        showWordCount: false,

        // Whether or not you want to show the Char Count
        showCharCount: true,

        // Whether or not you want to count Spaces as Chars
        countSpacesAsChars: false,

        // Whether or not to include Html chars in the Char Count
        countHTML: false,

        // Maximum allowed Word Count, -1 is default for unlimited
        maxWordCount: -1,

        // Maximum allowed Char Count, -1 is default for unlimited
        maxCharCount: 1500,

        // Option to limit the characters in the Editor, for example 200 in this case.
        charLimit: 1500,

        notification_duration: 1,
        duration: 1
    },
    notification: {
        duration: 1,
        notification_duration: 1
    }
});

$('.tokenfield').tokenfield({
    createTokensOnBlur: true
});

$('.form-check-input-styled').uniform();

$(document).on('click','#is_experience',function(){
    if($(this).is(":checked")){
        $(".btn-add-more-exp,.or_add").hide();
        $(".work-exp-details").fadeOut();
    }else{
        $(".btn-add-more-exp,.or_add").show();
        $(".work-exp-details").fadeIn();
    }
});


$(document).ready(function(){
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    });

    $.validator.addMethod("isUrlValid", function (url) {
        url = $.trim(url);
        if (url != '')
            return /^http:\/\/|https:\/\/|www\.(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        else return true;
    }, $.validator.format("Please enter valid link"));

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

    jQuery.validator.addMethod("extension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf|doc?x";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    });

    $.validator.addMethod('customphone', function (value, element) {
        return this.optional(element) || /^[+-]?\d+$/.test(value);
    }, "Please enter a valid phone number");

    var validator = $('.entrepreneur_profile_form').validate({
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
            console.log('error',error)
            if (element.parents('div').hasClass('account-img-content')) {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parents('div').hasClass('tokenfield')) {
                error.appendTo(element.parent().parent());
            } else if (element.parents('div').hasClass('form-group')) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            profile_image: {
                required: is_profile_exists,
                normalizer: function (value) { return $.trim(value); },
                extension: 'jpg|jpeg|png',
                filesize: 1048576,
            },
            name: {
                required: true,
                minlength: 2,
                maxlength: 50,
                normalizer: function (value) { return $.trim(value); },
                onlySpecialCharactersNotAllow: true,
                NotValidName: true,
                onlyDigitNotAllow: true,
            },
            gender: {
                required: true,
            },
            email: {
                required: true,
                emailValidation: true,
                maxlength: 255,
                noSpace: true,
            },
            phone: {
                required: true,
                customphone: true,
                maxlength: 15,
                minlength: 10,
                noSpace: true,
                normalizer: function (value) { return $.trim(value); },
            },
            fb_link: {
                isUrlValid: true,
            },
            web_link: {
                isUrlValid: true,
            },
            insta_link: {
                isUrlValid: true,
            },
            tw_link: {
                isUrlValid: true,
            },
            city: {
                required: true,
                alpha: true,
            },
            dob: {
                required: true,
            },
            skills: {
                required: true,
            },
            interests: {
                required: true,
            },
        },
        debug:true,
        messages: {
            profile_image: {
                required: "Please select profile picture",
                extension: "Accepted file formats: jpg, jpeg, png.",
                filesize: "file size must be less than 50 MB",
            },
            name: {
                required: "Please enter your name",
                minlength: jQuery.validator.format("At least {0} characters are required"),
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
                NotValidName: "Please enter valid name",
            },
            phone: {
                required: "Please enter your contact number",
                minlength: jQuery.validator.format("At least {0} characters are required"),
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
                customphone: "Only numbers are allowed",
            },
            email: {
                required: "Please enter your email",
                maxlength: jQuery.validator.format("Maximum {0} characters are allowed"),
                emailValidation: "Please enter valid email address",
            },
            gender: {
                required: "Please select gender",
            },
            fb_link: {
                required: "Please enter facebook link",
            },
            web_link: {
                required: "Please enter website link",
            },
            insta_link: {
                required: "Please enter instagram link",
            },
            tw_link: {
                required: "Please enter twitter link",
            },
            city: {
                required: "Please enter city",
                alpha: "The City may only contain letters and spaces.",
            },
            dob: {
                required: "Please select your birthdate",
            },
            skills: {
                required: "Please enter your skills",
            },
            interests: {
                required: "Please enter your interests",
            },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.about.updateElement();
            // $(form).find('button[type="submit"]').attr('disabled', 'disabled');
            // form.submit();
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

$(document).on('click','.btn-add-more-exp',function(){
    validateExtraField();

    var is_valid = true;
    $('#work-eperieance input').each(function() {
        $('.entrepreneur_profile_form').validate().element(this);
        if(!$(this).valid()){
            is_valid = $(this).valid();
        }
    });
    if(is_valid){
        $.uniform.restore(".work-exp-details .work-exp-item .form-check-input-styled");
        var workDetails = $(".work-exp-details .work-exp-item").eq(0).clone();
        ex_count++;
        
        workDetails.find('.exp_to_date input').prop('disabled',false);
        workDetails.find('.validation-error-label').remove();
        workDetails.find('.form-check-input-styled').prop('checked',false);
        workDetails.find('input.id').remove();
        workDetails.find('input').each(function() {
            this.name = this.name.replace(/\[\d\]+/, '[' + ex_count + ']');
            this.value = "";
        });
        $('.work-exp-details').append(workDetails);
        $('.work-exp-details .form-check-input-styled').uniform();
        workDetails.find('.work_is_present').attr('value', 1);
    }
});

$(document).on('click','.btn-add-more-edu',function(){
    validateEducationExtraField();

    var is_valid = true;
    $('#work-eperieance input').each(function() {
        $('.entrepreneur_profile_form').validate().element(this);
        if(!$(this).valid()){
            is_valid = $(this).valid();
        }
    });

    if(is_valid){
        $.uniform.restore(".education-details .education-item .form-check-input-styled");
        var eduDetails = $(".education-details .education-item").eq(0).clone();
        ed_count++;
        eduDetails.find('.edu_to_date input').prop('disabled',false);
        eduDetails.find('.validation-error-label').remove();
        eduDetails.find('.form-check-input-styled').prop('checked',false);
        eduDetails.find('input.id').remove();
        eduDetails.find('input').each(function() {
            this.name = this.name.replace(/\[\d\]+/, '[' + ed_count + ']');
            this.value = "";
        });
        $('.education-details').append(eduDetails);
        $('.education-details .form-check-input-styled').uniform();
        eduDetails.find('.edu_is_present').attr('value', 1);
    }
});

$(document).on('click','.delete-work-exp',function(e){
    var data_id = $(this).parents('.work-exp-item').find("input[type='hidden'].id").val();
    if($('#is_experience').is(":checked") || $('.work-exp-item').length > 1){
        $(this).parents('.work-exp-item').remove();

        if(data_id != "") {
            exp_array.push(data_id);
            $('#exp_remove_arr').val(JSON.stringify(exp_array));
        }
    }else{
        swal({
            title: "At least one required",
            // confirmButtonColor: "#FF7043",
            confirmButtonClass: 'btn btn-primary',
            type: "warning",
            confirmButtonText: "OK",
        });
    }
});

$(document).on('click','.delete-edu-exp',function(){
    var data_id = $(this).parents('.education-item').find("input[type='hidden'].id").val();
    if($('.education-item').length > 1){
        $(this).parents('.education-item').remove();

        if(data_id != "") {
            edu_array.push(data_id);
            $('#edu_remove_arr').val(JSON.stringify(edu_array));
        }
    }else{
        swal({
            title: "At least one required",
            // confirmButtonColor: "#FF7043",
            confirmButtonClass: 'btn btn-primary',
            type: "warning",
            confirmButtonText: "OK",
        });
    }
});


function validateExtraField(){
    $('.company_name').each(function() {
        $(this).rules('add', {
            required: { depends: function(element) { return !$('#is_experience').is(":checked") } },
            normalizer: function(value) {return $.trim(value);},
            maxlength: 100,
            messages: {
                required:  "Please enter company name",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });

    $('.designation').each(function() {
        $(this).rules('add', {
            required: { depends: function(element) { return !$('#is_experience').is(":checked") } },
            normalizer: function(value) {return $.trim(value);},
            maxlength: 100,
            messages: {
                required:  "Please enter designation",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
    $('.year').each(function() {
        $(this).rules('add', {
            required: { depends: function(element) { return !$('#is_experience').is(":checked") } },
            normalizer: function(value) {return $.trim(value);},
            maxlength: 100,
            messages: {
                required:  "Please enter year of experience",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
}

function validateEducationExtraField(){
    $('.course_name').each(function() {
        $(this).rules('add', {
            required: true,
            normalizer: function(value) {return $.trim(value);},
            maxlength: 100,
            messages: {
                required:  "Please enter course name",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
    $('.organization_name').each(function() {
        $(this).rules('add', {
            required: true,
            normalizer: function(value) {return $.trim(value);},
            maxlength: 100,
            messages: {
                required:  "Please enter college/organization name",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
    $('.percentage').each(function() {
        $(this).rules('add', {
            required: true,
            normalizer: function(value) {return $.trim(value);},
            maxlength: 50,
            messages: {
                required:  "Please enter your grade",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
    $('.education-details .year').each(function() {
        $(this).rules('add', {
            required: true,
            normalizer: function(value) {return $.trim(value);},
            maxlength: 50,
            messages: {
                required:  "Please enter total year of education",
                maxlength: "Maximum {0} characters are allowed",
            }
        });
    });
}

function readURL(element) {
    var permitted = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/jpg'];
    // console.log('element', element)
    // $('.updateProfile').validate().element(element);
    // $(".updateProfile").data('validator').element(element).valid();
    if (element.files && element.files[0]) {
        var file = element.files[0];
        if($.inArray(file['type'], permitted ) < 1){
            validator.element(element);
        }
        else{
            var reader = new FileReader();
            reader.onload = function (e) {
                // $('.imagedisplay').css('background-image', "url("+e.target.result+")");
                $('.account-img').attr("src",e.target.result);
            }
            reader.readAsDataURL(element.files[0]);
        }
    }
}