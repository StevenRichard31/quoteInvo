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
            $leftToPay =(float)str_replace(',',"",$leftToPay);
            //dump($leftToPay);
            $document = array_merge($document,['leftToPay' => $leftToPay]);
            //dump($document);die();
            return $document;
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
            $priceOutTaxe =  $product->getPriceOutTaxe();
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = $priceOutTaxe-$amountDiscount;
            }
            $taxe = $product->getTva()->getPercentage()/100;
            $amountOfTaxe = number_format(($priceOutTaxe * $taxe)*$product->getQuantity(),2);
            $product->setAmountOfTaxe((float)str_replace(',',"",$amountOfTaxe));
        }
    }

    public function setCountPriceWithTaxe($products){
        foreach ($products as $product){
            $priceOutTaxe =  $product->getPriceOutTaxe();
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = $priceOutTaxe-$amountDiscount;
            }
            $priceOutTaxe = number_format($priceOutTaxe,2);
            $priceOutTaxe =(float)str_replace(',',"",$priceOutTaxe);

           $countPriceWithTaxe =  ($priceOutTaxe*$product->getQuantity())+$product->getAmountOfTaxe();
           $countPriceWithTaxe = number_format($countPriceWithTaxe,2);
           $product->setPriceWithTaxe((float)str_replace(',',"",$countPriceWithTaxe));
        }
    }

    public function setCountTotalExcludingTaxes($document){
        $totalExcludingTaxes = 0;
        $products = $document->getProducts();
        foreach ($products as $product){

            $priceOutTaxe =  $product->getPriceOutTaxe();
            //si il y a un pourcentage de réduction
            if($product->getPercentageDiscount() != null){
                $amountDiscount = $priceOutTaxe * ($product->getPercentageDiscount()/100);
                $priceOutTaxe = $priceOutTaxe-$amountDiscount;
            }
            $totalExcludingTaxes += ($priceOutTaxe * $product->getQuantity());
        }
        $totalExcludingTaxes = number_format($totalExcludingTaxes,2);
        $document->setTotalExcludingTaxes((float)str_replace(',',"",$totalExcludingTaxes));
    }

    public function setCountSumTaxes($document){
        $sumTaxes = 0;
        $products = $document->getProducts();
        foreach ($products as $product){
            $sumTaxes += $product->getAmountOfTaxe();
        }

        $document->setSumTaxes($sumTaxes);
    }

    public function setCountTotalIncludingTaxes($document){
        $totalIncludingTaxes = number_format($document->getTotalExcludingTaxes()+ $document->getSumTaxes(),2);
        $document->setTotalIncludingTaxes((float)str_replace(',',"",$totalIncludingTaxes));
    }

}