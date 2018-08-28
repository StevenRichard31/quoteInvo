//select button addPhoneLink
var $newLinkLi = $('.add_phone_link');
var listPhones = $('.listPhones');


jQuery(document).ready(function() {


    /*----------------------------------------------------*/
    /*------ADD    PHONE                        ----------*/
    /*----------------------------------------------------*/
    // Get the ul that holds the collection of tags
    var collectionHolder = $('div.phones');

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    collectionHolder.data('index', (collectionHolder.find(':input').length)/2);


    $newLinkLi.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addPhoneForm(collectionHolder);
    });


});

function addPhoneForm(collectionHolder) {
    // get the new index
    var index = collectionHolder.data('index');

    const LIMIT_PHONE = 3;
    if ( index < LIMIT_PHONE){
        // Get the data-prototype explained earlier
        var prototype = collectionHolder.data('prototype');
        var newForm = prototype;

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        var newIndex = index + 1;

        // increase the index with one for the next item
        collectionHolder.data('index', newIndex);

        // Display the form in the page in an li, before the "Add a tag" link li
        listPhones.append(newForm);

    }
}

/*----------------------------------------------------*/
/*------DELETE PHONE                        ----------*/
/*----------------------------------------------------*/
/*
function addPhoneFormDeleteLink(newFormLi){


    var removeFormA = $('<div href="#" class="del_phone_link  btnSupprimer text-center" >Supprimer ce numéro de téléphone</div>');
    newFormLi.append(removeFormA);

    removeFormA.on('click', function(e) {

        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        newFormLi.remove();
    });
}*/

function deletePhone(id){
    $(id).remove();
}

/*----------------------------------------------------*/
/*------AUTOCOMPLETE                        ----------*/
/*----------------------------------------------------*/

//autocomplete for postal code and fill field-town
$('#appbundle_customer_address_postalCode').on('keyup', function() {

    $('#appbundle_customer_address_postalCode').autocomplete({
        source : function(requete, reponse){// les deux arguments représentent les données nécessaires au plugin
            var query = $('#appbundle_customer_address_postalCode').val();
            //request start at  field length = 3
            if(query.length > 2 &&(isNaN(query) === false)){

                getAddress(requete, reponse, query);

            };

        },
        //after select, start this action
        select: function( event, ui ) {
            fillFieldTown(ui.item.city);
        }
    });


});
//autocomplete for town and fill field-postalCode
$('#appbundle_customer_address_town').on('keyup', function() {

    $('#appbundle_customer_address_town').autocomplete({
        source : function(requete, reponse){// les deux arguments représentent les données nécessaires au plugin
            var query = $('#appbundle_customer_address_town').val();
            //request start at  field length = 3
            if(query.length > 2 &&(isNaN(query) === true)){
                getAddress(requete, reponse, query);
            };

        },
        //after select, start this action
        select: function( event, ui ) {
            fillFieldPostalCode(ui.item.code);
        }
    });


});


//fill field-town with value
function fillFieldTown(city){
    $( "#appbundle_customer_address_town" ).val(city);
}
//fill field-PostalCode with value
function fillFieldPostalCode(code){
    $( "#appbundle_customer_address_postalCode" ).val(code);
}

//request AJAX to API, get postalCode and nameCity
function getAddress(requete, reponse, query){
    console.log('requete');

    //request API
    $.ajax({
        url : "https://vicopo.selfbuild.fr/cherche/"+query, // on appelle le script JSON
        dataType : 'json', //type de données est en JSON
        success : function(donnee){
            reponse($.map(donnee.cities, function(objet){
                if (isNaN(query)){
                    return{
                        value: objet.city,
                        label:objet.code + ', ' + objet.city,
                        code:objet.code,
                    }
                }else{
                    return{
                        value: objet.code,
                        label:objet.code + ', ' + objet.city,
                        city:objet.city
                    }
                }

            }));

        }
    });
}