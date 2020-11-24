jQuery(document).ready(function($) {
	
	'use strict';
	
	/* Submenu Parent Style */
	$('.submenu li:first-child a').hover(function(){
		$(this).parent().parent().toggleClass('hover');
	});

	/* Sticky Header */
	jQuery(window).scroll(function() {
		if (jQuery(window).scrollTop() > 130) {
			jQuery('.one_header').addClass('sticky');
		}
		else {
			jQuery('.one_header').removeClass('sticky');
		}
	});


		jQuery(window).scroll(function() {
		if (jQuery(window).scrollTop() > 130) {
			jQuery('.valley_header').addClass('sticky');
		}
		else {
			jQuery('.valley_header').removeClass('sticky');
		}
	});
	
$('.top_inner.small_container > ul li a[href*=#]').click(function() { 
  if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
    var $target = $(this.hash);
    $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
    if ($target.length) {
   var targetOffset = $target.offset().top - 80;
   $('html,body')
   .animate({scrollTop: targetOffset}, 1000);
     return false;
    }
  }
 });
 // browser window scroll (in pixels) after which the "back to top" link is shown
 var offset = 500,
  //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
  offset_opacity = 1200,
  //duration of the top scrolling animation (in ms)
  scroll_top_duration = 1800,
  //grab the "back to top" link
  $back_to_top = $('.cd-top');

 //hide or show the "back to top" link
 $(window).scroll(function(){
  ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
  if( $(this).scrollTop() > offset_opacity ) { 
   $back_to_top.addClass('cd-fade-out');
  }
 });

 //smooth scroll to top
 $back_to_top.on('click', function(event){
  event.preventDefault();
  $('body,html').animate({
   scrollTop: 0 ,
    }, scroll_top_duration
  );
 }); 	




$('.owl-carousel').owlCarousel({
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