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