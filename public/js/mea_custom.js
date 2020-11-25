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
    
    
});