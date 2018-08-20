/*-------------------------------------------------------------------------------------------------------------*/
//select button addPhoneLink
var $newLinkLi = $('.add_product_link');

jQuery(document).ready(function() {
    //Champ utilisant 'SELECT2'
    $('.customerFormQuote').select2();
    $('.paymentMethodFormQuote').select2();
    $('.tvaForm').select2();


    /*----------------------------------------------------*/
    /*------ADD    PHONE                        ----------*/
    /*----------------------------------------------------*/
    // Get the ul that holds the collection of tags
    var collectionHolder = $('div.products');


    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    collectionHolder.data('index', (collectionHolder.find(':input').length)/2);


    $newLinkLi.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addProductForm(collectionHolder, $newLinkLi);
    });


});

function addProductForm(collectionHolder, $newLinkLi) {
    // get the new index
    var index = collectionHolder.data('index');

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
    var newFormLi = $('<div></div>').append(newForm);

    $('.add_product_row').before(newFormLi);

    //applique select2 sur la class
    $('.tvaForm').select2();



}

/*----------------------------------------------------*/
/*------DELETE PRODUCT                        ----------*/
/*----------------------------------------------------*/

function deleteProduct(id,idTtc,priceTotal){
    $(id).remove();
    removeOneTtc(idTtc,priceTotal);
    $('.allTotalTtc').text('Total TTC: '+ countTotalTtc(priceTotal).toFixed(2));
}

function removeOneTtc(id,priceTotal){
    delete priceTotal[id];
}
/*------------------------------------------------------------------------------------------*/
//Initialisation du champ TTC
$('.allTotalTtc').text('Total TTC: '+ countTotalTtc(priceTotal).toFixed(2));