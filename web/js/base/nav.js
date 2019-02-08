$(document).ready(function() {
    $('.nav-trigger').click(function() {
        $('.side-nav').toggleClass('visible');
    });

    //display navBar with phone screen
    $('.iconBar').click(function() {

        if($('.container-nav').is(':hidden')){
            $('.container-nav').removeClass("navHidden");
            $('.container-nav').addClass("navBlock");
            $('.container-nav').removeClass("animated fadeOutLeft");
            $('.container-nav').addClass("animated fadeInLeft");
        }
        else{
            $('.container-nav').removeClass("navBlock");
            $('.container-nav').removeClass("animated fadeInLeft");
            $('.container-nav').addClass("animated fadeOutLeft");
            setTimeout(function(){
                $('.container-nav').addClass("navHidden");
                $('.container-nav').removeClass("animated fadeOutLeft");
            }, 1000);


        }

    });



});

