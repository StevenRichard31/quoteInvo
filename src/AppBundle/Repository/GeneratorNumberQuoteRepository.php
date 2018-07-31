<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 05/06/2018
 * Time: 16:20
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class GeneratorNumberQuoteRepository extends EntityRepository
{



    public function findLastNumberQuote(){

        $sql = " 
            select number from generator_number_quote
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateLastNumberQuote($newLastNumberQuote, $lastNumberQuote){

        $sql = " 
            update generator_number_quote set number = :newLastNumberQuote where number = :lastNumberQuote
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(["newLastNumberQuote" => $newLastNumberQuote, "lastNumberQuote" => $lastNumberQuote]);
    }



}