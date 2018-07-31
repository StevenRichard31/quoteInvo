<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 22/06/2018
 * Time: 11:13
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Invoice;
use AppBundle\Entity\Quote;
use AppBundle\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class SearchManager
{

    private $doctrine;

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(){
        return new Search();
    }

    public function searchQuote(Search $search){

        return $this->doctrine
            ->getRepository(Quote::class)
            ->findAllBySearch($search);
    }

    public function searchInvoice(Search $search){

        return $this->doctrine
            ->getRepository(Invoice::class)
            ->findAllBySearch($search);
    }
}