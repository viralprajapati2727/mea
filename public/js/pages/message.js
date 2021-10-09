$(document).ready(function () {
    
    getActiveUserMessages();
    
    $(document).on('click', '.chat-section .user-band', function(){
        getActiveUserMessages()
    })

    $('.message-list').on("scroll", function() {
        var group_id = $('.chat-section .user-band .active').attr('data-group');

        var scrollHeight = $("#chat"+group_id+" .message-list").height();
        var scrollPosition = $("#chat"+group_id+" .message-list").height() + $("#chat"+group_id+" .message-list").scrollTop();
        // console.log('scrollHeight',scrollHeight);
        // console.log('scrollPosition',scrollPosition);
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
            // when scroll to bottom of the page
            // console.log('at top');   
            loadMessages(group_id);
        }
    });
    
    
    $("#f-send-message").validate({
        ignore: "input[type=hidden]",
        errorElement: "span",
        errorClass: "error",
        debug: true,
        rules: {
            type_msg: {
                required: true,
                maxlength: 255,
            },
        },
        messages: {
            type_msg: {
                required: "Please type message",
                maxlength: "Maximum 255 characters allowed",
            },
        },
        highlight: function (element, errorClass) {
            $(element).addClass("errorClass");
            $(element).addClass("error-border");
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass("errorClass");
            $(element).removeClass("error-border");
        },
        errorPlacement: function (error, element) {
            if (element.parents("div").hasClass("form-group")) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            $(form).find('button[type="submit"]').attr("disabled", true);
            $(form).ajaxSubmit({
                beforeSubmit: showRequest_pro_profile, // pre-submit callback
                error: errorResponse_pro_profile,
                success: showResponse_pro_profile, // post-submit callback
            });
        },
    });

    // pre-submit callback
    function showRequest_pro_profile(formData, jqForm, options) {
        jqForm.find(".submit-btn").attr("disabled", true);
        jqForm.find(".submit-icon").removeClass("fa-paper-plane");
    }

    // post-submit callback
    function showResponse_pro_profile(responseText, statusText, xhr, jqForm) {
        if (responseText.status == "1") {
            if (
                typeof responseText.redirect != "undefined" &&
                responseText.redirect !== null
            ) {
                location.href = responseText.redirect;
            } else {
                window.location.reload();
            }
        } else {
            jqForm.find(".submit-btn").attr("disabled", false);
            
            jqForm.find(".validation-error-label").remove();

            if (Object.keys(responseText.errors).length > 0) {
                $.each(responseText.errors, function (idx, obj) {
                    if (idx.indexOf("[]") != -1 || idx == "gender") {
                        idx = idx.replace(/\[]/g, "");
                        obj[0] = obj[0].replace(/\[]/g, "");
                        if (idx == "gallery-photo-add") {
                            $(".gallary-validation-server").html(obj[0]).show();
                        } else {
                            $("#" + idx).addClass("error-border");
                            $("#" + idx)
                                .parent("div")
                                .append(
                                    '<span id="' +
                                        idx +
                                        '-error" class="validation-error-label server-error">' +
                                        obj[0] +
                                        "</span></div>"
                                );
                        }
                    } else {
                        $("input[name='" + idx + "']").addClass("error-border");
                        $("input[name='" + idx + "']")
                            .parent("div")
                            .append(
                                '<span id="' +
                                    idx +
                                    '-error" class="validation-error-label server-error">' +
                                    obj[0] +
                                    "</span></div>"
                            );
                    }
                });
            } else {
                $(".flash-messages").html(
                    '<div class="d-block error-message custom-error">\n' +
                        '<div class="alert alert-danger alert-block">\n' +
                        '\t<button type="button" class="close" data-dismiss="alert">×</button>\n' +
                        "    <span>" +
                        responseText.message +
                        "</span>\n" +
                        "</div>\n" +
                        "</div>"
                );
                window.scrollTo(0, 0);
            }
            jqForm
                .find(".server-error:first")
                .parent("div")
                .find("input")
                .focus();
        }
    }

    function errorResponse_pro_profile(xhr, textStatus, errorThrown, jqForm) {
        console.log(errorThrown);
        // location.href = base_url;
        jqForm.find(".submit-btn").attr("disabled", false);
        // jqForm.find(".submit-btn").addClass("flaticon-save");
        // jqForm.find(".submit-btn").removeClass("fa fa-lg fa-refresh fa-spin");
    }

    $('#f-send-message').submit( function(){
        
        // $('.submit-btn').attr("disabled", "disabled");
    })
});

function getActiveUserMessages(){
    var group_id = $('.chat-section .user-band .active').attr('data-group');
    var activated_user_name = $('.chat-section .user-band .active .user-name').text();
    $('.activated-user-name').text(activated_user_name);
    $('#f-send-message .g_id').val(group_id);
    loadMessages(group_id)
}

function loadMessages(group_id){
    var page = $("#chat"+group_id+" .group-options .page").val();
    $.ajax(
    {
        url: get_ajax_message_list,
        type: "POST",
        data:{group_id:group_id, page: page},
        beforeSend: function()
        {                    
            // blockContentBody(); 
        }
    })
    .done(function(data)
    {                
        if(data.html == ""){
            //   $(".no-record").show();
            //   unBlockContentBody();
            return;
        }else{
            // unBlockContentBody();
            $("#chat"+group_id+" .message-list").prepend(data.html);
            // $("#chat"+group_id+" .message-list").scrollBy(0, 500);
            if(page == 1){
                $("#chat"+group_id+" .message-list").animate({ scrollTop: $("#chat"+group_id+" .message-list").height() }, 500);
            }
            $("#chat"+group_id+" .group-options .page").val(data.next_page);
        }
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        console.log(jqXHR);
        console.log(ajaxOptions);
        console.log(thrownError);                
    // unBlockContentBody();
    });
}
