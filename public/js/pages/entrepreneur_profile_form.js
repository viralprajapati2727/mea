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