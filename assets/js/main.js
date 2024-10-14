jQuery(function ($) {
    'use strict';
   
    // Header Sticky
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 120) {  
            $('.navbar').addClass("is-sticky");
        } else {
            $('.navbar').removeClass("is-sticky");
        }
    });

    // Navbar JS
    $('.navbar .navbar-nav li a').on('click', function(e){
        var anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $(anchor.attr('href')).offset().top - 10
        }, 50);
        e.preventDefault();
    });

    $(document).on('click', '.navbar-collapse.in', function(e) {
        if ($(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle') {
            $(this).collapse('hide');
        }
    });

    $('.navbar .navbar-nav li a').on('click', function(){
        $('.navbar-collapse').collapse('hide');
        $('.burger-menu').removeClass('active');
    });

    // FAQ Accordion
    $(function() {
        $('.accordion').find('.accordion-title').on('click', function(){
            // Adds Active Class
            $(this).toggleClass('active');
            // Expand or Collapse This Panel
            $(this).next().slideToggle('fast');
            // Hide The Other Panels
            $('.accordion-content').not($(this).next()).slideUp('fast');
            // Removes Active Class From Other Titles
            $('.accordion-title').not($(this)).removeClass('active');        
        });
    });

    // Remove .switch-box div
    $(document).ready(function() {
        $('.switch-box').remove();
    });

}(jQuery));


