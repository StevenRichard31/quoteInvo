var priceTotal = {};
function countTotalTtc(priceTotal){
    var total = 0;
    for(element in priceTotal){
        total += priceTotal[element];
    }
    return total;
}