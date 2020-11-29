$(document).ready(function() {
    $('#home-slider').owlCarousel({
        loop:true,
        margin:10,
        responsiveClass:true,
        autoHeight: false,
        autoplay: false,
        autoPlaySpeed: 1000,
        nav:true,
        items:1,  
    });
    $( ".owl-prev").html('<i class="fa fa-arrow-left"></i>');
    $( ".owl-next").html('<i class="fa fa-arrow-right"></i>');
    
    //for register form
    $(document).on('click','input[name=user_type]',function(){
        $(this).parents('.form-radio-group').find('label.radio-inline').removeClass('active');
        $(this).parents('label.radio-inline').addClass('active');
    });

    $('div.alert').not('.alert-important').delay(5000).fadeOut(350);

    $('.select2').select2({
        // minimumResultsForSearch: Infinity
    });

    
});