<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 08/06/2018
 * Time: 07:17
 */

namespace Components\Utils;


class CountFunction
{

    public function setAllCount($document){

        $this->setCountAmountOfTaxe($document->getProducts());
        $this->setCountPriceWithTaxe($document->getProducts());
        $this->setCountTotalExcludingTaxes($document);
        $this->setCountSumTaxes($document);
        $this->setCountTotalIncludingTaxes($document);

    }

    public function setTVA($document){
        return $this->setAllTVA($document);

    }

    public function setLeftToPay($document){

        if($document[0]->getPercentageAdvencePayment() != null){
            $advence = $document[0]->getPercentageAdvencePayment()/100;
            $total = $document[0]->gettotalIncludingTaxes();
            $amountAdvence = $total*$advence;
            $leftToPay = number_format($total-$amountAdvence,2);
            return $document = array_merge($document,['leftToPay' => $leftToPay]);
        }
        return $document;

    }

    public function setAllTVA($document){

        $products = $document[0]->getProducts();
        $i = 0;
        $listTva=[];
        $arrayTva = [];

        foreach ($products as $product){

            $tvaName =$product->getTva()->getName();
            $tvaPrice= $product->getAmountOfTaxe();

            for($b =0 ; $b<sizeof($arrayTva); $b++){
                if($arrayTva[$b]['name'] == $tvaName){
                    $arrayTva[$b]['price'] += $tvaPrice;
                    break;
                }
            }
            if(!in_array($tvaName,$listTva)){
                $listTva[] = $tvaName;
                $arrayTva[$i]['name']=$tvaName;
                $arrayTva[$i]['price'] = $tvaPrice;
                $i += 1;
                ;
            }

        }

        $arrayTva = ["arrayTva" => $arrayTva];
        return $document = array_merge($document, $arrayTva);
    }

    public function setCountAmountOfTaxe($products){
        foreach ($products as $product){
            $priceOutTaxe =  number_format($product->getPriceOutTaxe(),2);
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = number_format($priceOutTaxe-$amountDiscount,2);
            }
            $taxe = $product->getTva()->getPercentage()/100;
            $amountOfTaxe = number_format(($priceOutTaxe * $taxe)*$product->getQuantity(),2);
            $product->setAmountOfTaxe($amountOfTaxe);
        }
    }

    public function setCountPriceWithTaxe($products){
        foreach ($products as $product){
            $priceOutTaxe =  number_format($product->getPriceOutTaxe(),2);
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = number_format($priceOutTaxe-$amountDiscount,2);
            }
           $countPriceWithTaxe =  number_format(($priceOutTaxe*$product->getQuantity())+$product->getAmountOfTaxe(),2);
           $product->setPriceWithTaxe($countPriceWithTaxe);
        }
    }

    public function setCountTotalExcludingTaxes($document){
        $totalExcludingTaxes = 0;
        $products = $document->getProducts();

        foreach ($products as $product){
            $priceOutTaxe =  number_format($product->getPriceOutTaxe(),2);
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = number_format($priceOutTaxe-$amountDiscount,2);
            }
            $totalExcludingTaxes += ($priceOutTaxe * $product->getQuantity());
        }
        $totalExcludingTaxes = number_format($totalExcludingTaxes,2);
        $document->setTotalExcludingTaxes($totalExcludingTaxes);
    }

    public function setCountSumTaxes($document){
        $sumTaxes = 0;
        $products = $document->getProducts();
        foreach ($products as $product){
            $sumTaxes += $product->getAmountOfTaxe();
        }
        $sumTaxes = number_format($sumTaxes,2);
        $document->setSumTaxes($sumTaxes);
    }

    public function setCountTotalIncludingTaxes($document){
        $totalIncludingTaxes = number_format($document->getTotalExcludingTaxes()+ $document->getSumTaxes(),2);
        $document->setTotalIncludingTaxes($totalIncludingTaxes);
    }
/*
    public function sortArrayForPDF($quotes){

        //tableau trier vide
        //$sortArray = [];
        //taille de la liste des devis non trier
        $sizeArray = sizeof($quotes);

        //initialize variable
        $oldID = null;
        $newArray = [];
        $products = [];
        $product = [];
        $totalHT = 0;
        $percentage = 0;
        $percentageAdvencePayment = 0;
        $validationDeadLine = null;

        for ($i = 0 ; $i < $sizeArray ; $i++){

            $percentage = $quotes[$i]['percentage'];
            if($quotes[$i]['percentage_advence_payment'] != null){
                $percentageAdvencePayment = $quotes[$i]['percentage_advence_payment'];
            }

            $id = $quotes[$i]['documentID'];

            //si nouveau devis et pas le premier devis
            if( $oldID !== $id && $oldID != null){
                //on donne un nom à la table "products"
                $products=["products"=> $products];
                //on concatène les 2 tableaux
                $newArray=array_merge($newArray,$products);
                //on rempli le tableau devis avec un tableau final d'un devis trier
                //array_push($sortArray, $newArray);
                //on vide le tableau du devis
                $newArray = [];
                //on vide la liste des products du devis
                $products =[];
            }

            //si nouveau devis
            if( $oldID !== $id){
                //on passe l'id du devis en 'ancien'
                $oldID = $id;
                $totalHT += $quotes[$i]['quantity']*$quotes[$i]['unit_price']-($quotes[$i]['quantity']*$quotes[$i]['unit_price'])*($quotes[$i]['percentage_discount']/100);

                if(isset($quotes[$i]['validation_deadline'])){
                    $validationDeadLine = $quotes[$i]['validation_deadline'];
                }

                //on rempli un tableau des information du devis sauf "products"
                $newArray =["id" => $quotes[$i]['documentID'],
                    "customerName" => $quotes[$i]['customerName'],
                    "mail" => $quotes[$i]['mail'],
                    "building" => $quotes[$i]['building'],
                    "country" => $quotes[$i]['country'],
                    "postal_code" => $quotes[$i]['postal_code'],
                    "street" => $quotes[$i]['street'],
                    "town" => $quotes[$i]['town'],
                    "creation_date" => $quotes[$i]['creation_date'],
                    "validation_deadline" => $validationDeadLine,
                    "billing_date" => $quotes[$i]['billing_date'],
                    "document_name" => $quotes[$i]['document_name'],
                    "paymentMethodName" => $quotes[$i]['paymentMethodName'],
                    "tvaName" => $quotes[$i]['tvaName'],
                    "numberDocument" => $quotes[$i]['numberDocument'],
                    "percentage" => $quotes[$i]['percentage'],
                    "building" => $quotes[$i]['building'],
                    "country" => $quotes[$i]['country'],];



                $product = [
                    "productName" => $quotes[$i]['productName'] ,
                    "quantity" => $quotes[$i]['quantity'],
                    "percentage_discount" => $quotes[$i]['percentage_discount'],
                    "unit_price" => $quotes[$i]['unit_price'],
                    "total" => number_format($quotes[$i]['quantity']*$quotes[$i]['unit_price']-($quotes[$i]['quantity']*$quotes[$i]['unit_price'])*($quotes[$i]['percentage_discount']/100),2)
                ];
                //on rempli "products" avec un "product"
                array_push($products,$product );
                $product = [];


            }//si meme devis
            elseif ($oldID === $id){
                $totalHT += $quotes[$i]['quantity']*$quotes[$i]['unit_price']-($quotes[$i]['quantity']*$quotes[$i]['unit_price'])*($quotes[$i]['percentage_discount']/100);
                $product = [
                    "productName" => $quotes[$i]['productName'],
                    "quantity" => $quotes[$i]['quantity'],
                    "percentage_discount" => $quotes[$i]['percentage_discount'],
                    "unit_price" => $quotes[$i]['unit_price'],
                    "total" => number_format($quotes[$i]['quantity']*$quotes[$i]['unit_price']-($quotes[$i]['quantity']*$quotes[$i]['unit_price'])*($quotes[$i]['percentage_discount']/100),2)
                ];
                array_push($products,$product );
                $product = [];
            }

        }

        $taxeTVA = $totalHT * ($percentage/100);
        $totalTTC = $taxeTVA + $totalHT;
        $acompte = $totalTTC * ($percentageAdvencePayment/100);
        $toPay = $totalTTC - $acompte;

        $toPay = number_format($toPay,2);
        $toPay = ["toPay" => $toPay];
        $newArray=array_merge($newArray,$toPay);

        $acompte = number_format($acompte,2);
        $acompte = ["acompte" => $acompte];
        $newArray=array_merge($newArray,$acompte);

        $totalTTC = number_format($totalTTC,2);
        $totalTTC = ["totalTTC" => $totalTTC];
        $newArray=array_merge($newArray,$totalTTC);

        $taxeTVA = number_format($taxeTVA, 2);
        $taxeTVA = ["taxeTVA" => $taxeTVA];
        $newArray=array_merge($newArray,$taxeTVA);

        $totalHT = number_format($totalHT,2);
        $totalHT = ["totalHT" => $totalHT];
        $newArray=array_merge($newArray,$totalHT);


        $products=["products"=> $products];
        $newArray=array_merge($newArray,$products);
        //array_push($sortArray, $newArray);

        //on retourne le tableau trier
        return $newArray;

    }
*/
}