$(document).ready(function(){
    $("#job_start_time").datetimepicker({
        ignoreReadonly: true,
        format: 'LT',
        useCurrent: false,
        locale: 'en'
    });
    $("#job_end_time").datetimepicker({
        ignoreReadonly: true,
        format: 'LT',
        useCurrent: false,
        locale: 'en',
    });
    
    $(document).on('change','#job_title_id',function(){
        $('.div_other_job_title').hide();
        if($(this).val() == '-1'){
            $('.div_other_job_title').show();
        }
    });

    $('.form-check-input-styled').uniform();
    $(document).on('change','.is_find_team_member',function(){
        $('.team_member_text_div').hide();
        if($(this).is(':checked')){
            $('.team_member_text_div').show();
        }
    });
    
    $(document).on('change','.is_paid',function(){
        $('.salary_div').hide();
        if($(this).is(':checked')){
            $('.salary_div').show();
        }
    });


    // $('.type_post_request').hide();
    $(document).on('click','input[name=job_type]',function(){
        $(this).parents('.form-radio-group').find('label.radio-inline').removeClass('active');
        $(this).parents('label.radio-inline').addClass('active');

        if($(this).val() == '1'){
            $('.type_post_job').show();
            $('.type_post_request').hide();
        } else {
            $('.type_post_job').hide();
            $('.type_post_request').show();
        }
    });

    //shift radio buttons
    $('.job_shift_wrap .radio-inline').on('click',function(){
        // $(this).parent('.form-radio-group').find('label.radio-inline').removeClass('active');
        if($(this).parent('.form-radio-group').find('.shift-radio').is(':checked')){
            $(this).addClass('active');
        }
    });


    CKEDITOR.replaceAll('description', {
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

    CKEDITOR.replaceAll('r_description', {
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

    $.validator.addMethod("greaterThan", function(value, element, params) {
        if($(params).val() != '' && value != ''){
            value = new Date(moment(value, ["h:mm A"]).format("MM/DD/YY HH:mm"))
            params = new Date(moment($(params).val(), ["h:mm A"]).format("MM/DD/YY HH:mm"))
            if (!/Invalid|NaN/.test(value)) {
                console.log(value > params)
                return value > params;
            }
            return isNaN(value) && isNaN(params) || (Number(value) > Number(params));
        }
        return true;
    });

    $.validator.addMethod("greaterThanSalary", function(value, element, params) {
        var min_salary = $(params).val();

        if(min_salary != '' && value != ''){
            return isNaN(value) && isNaN($(params).val()) || Number(value) > Number(min_salary);
        }
        return true;
    });

    $.validator.addMethod("checkCkeditorEmpty", function(value, element, params) {

        var editorcontent = $(params).val().replace(/<[^>]*>/gi, ''); // strip tags
        var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
        if(Number(editor_value) === 0){
            return false;
        };
        return true;
    });

    // for postjob
    $('.post-job-form').validate({
        
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        debug: true,
        errorClass: 'error',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        // Different components require proper error label placement
        errorPlacement: function(error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }
            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }
            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('multiselect-native-select')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('cv_required')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('ckeditor')) {
                error.appendTo( element.parent().parent());
            }
            else if (element.parents('div').hasClass('key_skill')) {
                error.appendTo( element.parent().parent());
            }
            // Other elements
            else {
                error.insertAfter(element);
            }
        },
        rules: {
            job_title_id: {
                required: true,
            },
            other_job_title:{
                required: { depends: function(element) { return $('#job_title_id').val() == "-1" } },
                maxlength:100,
            },
            business_category_id: {
                required: true,
            },
            job_type_id: {
                required: true,
            },
            currency_id: {
                required: { depends: function(element) { return $('.is_paid').is(":checked") } },
            },
            min_salary: {
                required: { depends: function(element) { return $('.is_paid').is(":checked") } },
                digits: true,
                maxlength:8,
            },
            max_salary: {
                required: { depends: function(element) { return $('.is_paid').is(":checked") } },
                digits: true,
                maxlength:9,
                greaterThanSalary:".min_salary",
            },
            salary_type_id: {
                required: { depends: function(element) { return $('.is_paid').is(":checked") } },
            },
            job_start_time: {
            },
            job_end_time: {
                greaterThan: ".job_start_time",
            },
            location: {
                required: true,
                maxlength:255,
            },
            key_skills: {
                required: true,
            },
            description: {
                required: function(){
                    CKEDITOR.instances.description.updateElement();
                    var editorcontent = $('#description').val().replace(/<[^>]*>/gi, ''); // strip tags
                    var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
                    return Number(editor_value) === 0;
                },
                checkCkeditorEmpty: '#description',
            },
        },
        messages: {
            job_title_id: {
                required: "Please select job title",
            },
            other_job_title: {
                required: "Please enter other job title",
            },
            business_category_id: {
                required: "Please select business category",
            },
            job_type_id: {
                required: "Please select job type",
            },
            currency_id: {
                required: "Please select currency",
            },
            min_salary: {
                required: "Please enter minimum salary",
                digits: "Please enter only number value",
                maxlength: "Maximum {0} characters are allowed",
            },
            max_salary: {
                required: "Please enter maximum salary",
                digits: "Please enter only number value",
                greaterThanSalary: "Please enter max salary greater than min salary",
                maxlength: "Maximum {0} characters are allowed",

            },
            job_start_time: {
                required: "Please enter start time",
            },
            job_end_time: {
                required: "Please enter end time",
                greaterThan: "Please enter end time greater than start time",
            },
            key_skills: {
                required: "Please enter key skills",
            },
            location: {
                required: "Please enter job address",
                maxlength: "Maximum {0} characters are allowed",
            },
            description: {
                required: "Please enter job description",
                checkCkeditorEmpty: "Please enter job description",
            },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.description.updateElement();
            $('.post-job-form').ajaxSubmit(
                {
                    beforeSubmit:  showRequest_pro_profile,  // pre-submit callback
                    success:       showResponse_pro_profile,  // post-submit callback
                    error: errorResponse_pro_profile,
                }
            );
        }
    });

    //for postrequest
    $('.post-request-form').validate({
        
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        debug: true,
        errorClass: 'error',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        // Different components require proper error label placement
        errorPlacement: function(error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }
            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }
            else if (element.parents('div').hasClass('multiselect-native-select')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('ckeditor')) {
                error.appendTo( element.parent().parent());
            }
            // Other elements
            else {
                error.insertAfter(element);
            }
        },
        rules: {
            other_job_title:{
                required: true,
                maxlength:100,
            },
            location: {
                maxlength:255,
            },
            description: {
                required: function(){
                    CKEDITOR.instances.description.updateElement();
                    var editorcontent = $('#r_description').val().replace(/<[^>]*>/gi, ''); // strip tags
                    var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
                    return Number(editor_value) === 0;
                },
                checkCkeditorEmpty: '#r_description',
            },
        },
        messages: {
            other_job_title: {
                required: "Please enter post title",
            },
            location: {
                required: "Please enter address",
                maxlength: "Maximum {0} characters are allowed",
            },
            r_description: {
                required: "Please enter post description",
                checkCkeditorEmpty: "Please enter job description",
            },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.r_description.updateElement();
            $('.post-request-form').ajaxSubmit(
                {
                    beforeSubmit:  showRequest_pro_profile,  // pre-submit callback
                    success:       showResponse_pro_profile,  // post-submit callback
                    error: errorResponse_pro_profile,
                }
            );
        }
    });
});

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