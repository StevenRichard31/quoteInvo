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

    public function setLeftToPayAndTVA($document){
        $document = $this->setAllTVA($document);
        return $this->setLeftToPay($document);
    }

    public function setLeftToPay($document){

        if($document[0]->getPercentageAdvencePayment() != null){
            $advence = $document[0]->getPercentageAdvencePayment()/100;
            $total = $document[0]->getTotalIncludingTaxes();
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

}