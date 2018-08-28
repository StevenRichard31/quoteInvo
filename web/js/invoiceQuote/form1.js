var priceTotal = {};
function countTotalTtc(priceTotal){
    var total = 0;
    for(element in priceTotal){
        total += priceTotal[element];
    }
    return total;
}

/*----------------------------------------------------*/
/*------FUNCTION COUNT FIELDS                       ----------*/
/*----------------------------------------------------*/
function actionListener(id,nameID){

    const val = $('#'+id+nameID).val();
    console.log(nameID);
    console.log('#'+id+nameID);
    let valReplace = val.replace(',','.');
    let valeur = parseFloat(valReplace).toFixed(2);

    if(Number(valeur)){
        window[id+nameID] = valeur;
    }else{
        window[id+nameID] = 0;
    }
    console.log(window[id+nameID]);
    var result = count(window[id+'_quantity'],window[id+'_priceOutTaxe'],window[id+'_percentageDiscount'],window[id+'_tva']);
    refresh(result,'#'+id+'_ttc',priceTotal);
}
function refresh(val,fieldTtc,priceTotal){
    $(fieldTtc).text(val.toFixed(2));
    priceTotal[fieldTtc]= val;
    total = countTotalTtc(priceTotal);
    $('.allTotalTtc').text('Total TTC: '+total.toFixed(2));
}
function count(quantity,price,discount,tva){
    var percentTva = tva /100;
    var percentDiscount = discount / 100;
    var val = quantity*price;
    var priceDiscount =  val - (val*percentDiscount);
    var priceWithTaxe = priceDiscount +(priceDiscount*percentTva);
    return priceWithTaxe;
}