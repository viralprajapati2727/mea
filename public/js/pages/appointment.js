var d = new Date();
var Y = d.getFullYear();
var M = parseInt(d.getMonth());
var D = parseInt(d.getDate()) + 1;
$(".birthdate").datetimepicker({
    ignoreReadonly: true,
    useCurrent: false,
    format: 'MM/DD/YYYY',
    minDate: new Date(Y, M, D),
    // disabledDates: [
    //     new Date(Y, M, D)
    // ],
});

$("#appointment_time").datetimepicker({
    ignoreReadonly: true,
    format: 'LT',
    useCurrent: false,
    locale: 'en'
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
            appointment_time: {
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
            appointment_time: {
                required: "Please select appointment time",
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



    //detail page
    $('.appointment-detail').on('click',function(){
        var id = $(this).attr('data-id');
        $.ajax({
            url: appointment_detail_link,
            type: 'POST',
            data: { id : id, },
            beforeSend: function(){
                $('body').block({
                    message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.15,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            },
            success: function(response) {
                $('#appointment-detail .app_name').text(response.data.name);
                $('#appointment-detail .app_email').text(response.data.email);
                $('#appointment-detail .app_date').text(response.data.date);
                $('#appointment-detail .app_appointment_time').text(response.data.appointment_time);
                $('#appointment-detail .app_time').text(response.data.time);
                $('#appointment-detail .app_description').text(response.data.description);
                $('#appointment-detail').modal('show')
            },
            complete: function(){
                $('body').unblock();
            }
        });
    });

    //delete appointment
    $(document).on('click','.delete-appointment', function() {
        var id= $(this).data('id');
        var deleted= $(this).data('inuse');
        
        var dialog_title = 'Are you sure want to delete this appointment ?';
        
        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: appointment_delete_link,
                    type: 'POST',
                    data: { id : id, },
                    beforeSend: function(){
                        $('body').block({
                            message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    success: function(response) {
                        if(response.status == 200){
                            location.reload();
                        } else if(response.status == 201){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#FF7043",
                                confirmButtonClass: 'btn btn-warning',
                                type: "warning",
                                confirmButtonText: 'OK',
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    complete: function(){
                        $('body').unblock();
                    }
                });
            }
        }, function (dismiss) {
        });
    });


    $(document).on('click','.approve-reject', function() {
        var $this = $(this);
        var id = $this.attr('data-id');
        var status = $this.attr('data-active');
        var dialog_title = (status == 1 ? "Are you sure you want to approve this appointment?" : "Are you sure you want to reject this appointment?");

        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: approve_reject_link,
                    type: 'POST',
                    data: { id : id, status : status},
                    beforeSend: function(){
                        $('body').block({
                            message: '<i class="icon-spinner4 spinner"></i><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (m){
                                window.location.reload();
                                
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    complete: function(){
                        $('body').unblock();
                    }
                });
            }
        });
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
            var alertClass = "alert-danger";
            if (responseText.status == "200") {
                alertClass = "alert-success";
            }
            $(".flash-messages").html('<div class="d-block error-message home-globle-error">\n' + '<div class="alert ' + alertClass + ' alert-block">\n' +
                '\t<button type="button" class="close" data-dismiss="alert">×</button>\n' + "<span>" + responseText.message +
                "</span>\n" + "</div>\n" + "</div>"
            );
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