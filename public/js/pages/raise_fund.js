$(document).ready(function () {
    $.validator.addMethod("checkCkeditorEmpty", function(value, element, params) {
        var editorcontent = $(params).val().replace(/<[^>]*>/gi, ''); // strip tags
        var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
        if(Number(editor_value) === 0){
            return false;
        };
        return true;
    });

    CKEDITOR.replaceAll("description", {
        height: "200px",
        removeButtons: "Subscript,Superscript,Image",
        toolbarGroups: [
            { name: "styles" },
            { name: "editing", groups: ["find", "selection"] },
            { name: "basicstyles", groups: ["basicstyles"] },
            {
                name: "paragraph",
                groups: ["list", "indent", "blocks", "align"],
            },
            { name: "links" },
            { name: "insert" },
            { name: "colors" },
            { name: "tools" },
            { name: "others" },
            { name: "document", groups: ["mode", "document", "doctools"] },
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
            duration: 1,
        },
        notification: {
            duration: 1,
            notification_duration: 1,
        },
    });

    // for startup-portal-form
    $(".raise-fund-form").validate({
        ignore: "input[type=hidden], .select2-search__field", // ignore hidden fields
        debug: true,
        errorClass: "error",
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
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
            else {
                error.insertAfter(element);
            }
        },
        rules: {
            title: {
                required: true,
            },
            description: {
                required: function () {
                    CKEDITOR.instances.description.updateElement();
                    var editorcontent = $("#description")
                        .val()
                        .replace(/<[^>]*>/gi, ""); // strip tags
                    var editor_value = $.trim(
                        editorcontent.replace(/&nbsp;/g, "")
                    );
                    return Number(editor_value) === 0;
                },
                checkCkeditorEmpty: "#description",
            },
            amount: {
                required: true,
            },
            currency: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
            },
            description: {
                required: "Please enter description",
                checkCkeditorEmpty: "Please enter description",
            },
            currency: {
                required: "Please select currency",
            },
            amount: {
                required: "Please enter amount",
            },
        },
        submitHandler: function (form) {
            CKEDITOR.instances.description.updateElement();
            $(".raise-fund-form").ajaxSubmit({
                beforeSubmit: showRequest_pro_profile, // pre-submit callback
                success: showResponse_pro_profile, // post-submit callback
                error: errorResponse_pro_profile,
            });
        },
    });
});

// pre-submit callback
function showRequest_pro_profile(formData, jqForm, options) {
    jqForm.find(".submit-btn").attr("disabled", true);
    jqForm.find(".submit-icon").removeClass("flaticon-save");
    jqForm.find(".submit-icon").addClass("fa fa-lg fa-refresh fa-spin");
    jqForm.find(".submit-icon").removeClass("flaticon-save-file-option");
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
            location.href = base_url;
        }
    } else {
        jqForm.find(".submit-btn").attr("disabled", false);
        jqForm.find(".submit-icon").addClass("flaticon-save");
        jqForm.find(".submit-icon").removeClass("fa fa-lg fa-refresh fa-spin");
        jqForm.find(".submit-icon").addClass("flaticon-save-file-option");
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
        jqForm.find(".server-error:first").parent("div").find("input").focus();
    }
}

function errorResponse_pro_profile(xhr, textStatus, errorThrown, jqForm) {
    console.log(errorThrown);
    // location.href = base_url;
    jqForm.find(".submit-btn").attr("disabled", false);
    jqForm.find(".submit-btn").addClass("flaticon-save");
    jqForm.find(".submit-btn").removeClass("fa fa-lg fa-refresh fa-spin");
}
