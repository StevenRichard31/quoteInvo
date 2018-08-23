
$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('whatever'); // Extract info from data-* attributes
    var modal = $(this);
    modal.find('.modal-title').text('New message to ');
    modal.find('.btnSupprimer').attr('href' , url);

});

function displayCustomerInfo(id){

    var info = document.getElementById('info'+id);



    if (info.style.display === "none") {

        //cache les INFO CUSTOMER
        $('.infoCustomer').hide();

        //LIST CUSTOMER
        //donne la taille de la list
        $('.listCustomer').removeClass("col-lg-10");
        $('.listCustomer').removeClass("col-md-5");
        $('.listCustomer').removeClass("col-sm-8");
        $('.listCustomer').removeClass("col-5");
        $('.listCustomer').addClass("col");
        //donne l'animation de la list
        $('.listCustomer').removeClass("animated zoomIn");
        $('.listCustomer').addClass("animated slideInLeft");

        //INFO CUSTOMER
        $(info).removeClass("animated zoomOutRight");
        $(info).addClass("animated slideInRight");
        info.style.display = "block";

    }
    else if(info.style.display === "block") {

        //LIST CUSTOMER
        $('.listCustomer').removeClass("animated slideInLeft");
        $('.listCustomer').addClass("animated zoomOutLeft");
        setTimeout(function(){
            //donne la taille de la list
            $('.listCustomer').removeClass("col");
            $('.listCustomer').addClass("col-lg-10");
            $('.listCustomer').addClass("col-md-5");
            $('.listCustomer').addClass("col-sm-8");
            $('.listCustomer').addClass("col-5")
            //donne l'animation de la list
            $('.listCustomer').removeClass("animated zoomOutLeft");
            $('.listCustomer').addClass("animated zoomIn");
        }, 1000);

        //INFO CUSTOMER
        $(info).removeClass("animated slideInRight");
        $(info).addClass("animated zoomOutRight");
        setTimeout(function(){ info.style.display = "none"; }, 1000);


    }

}
