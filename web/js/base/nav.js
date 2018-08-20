$(document).ready(function() {
    $('.nav-trigger').click(function() {
        $('.side-nav').toggleClass('visible');
    });

    //display navBar with phone screen
    $('.iconBar').click(function() {
        var nav = $('.container-nav');
        if($('.container-nav').is(':hidden')){
            $('.container-nav').css('display', 'block');
            $('.container-nav').removeClass("animated fadeOutLeft");
            $('.container-nav').addClass("animated fadeInLeft");
        }
        else{
            $('.container-nav').removeClass("animated fadeInLeft");
            $('.container-nav').addClass("animated fadeOutLeft");
            setTimeout(function(){ $('.container-nav').css('display', 'none'); }, 1000);


        }

    });
});