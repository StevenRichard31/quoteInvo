<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/07/2018
 * Time: 14:28
 */

namespace AppBundle\Manager;

use AppBundle\Entity\GeneratorNumberQuote;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;

class GeneratorNumberQuoteManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;
    private $repository;
    private $firstNumberQuote;
    private $lastNumberQuote;



    /**
     * GeneratorNumberQuoteManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(GeneratorNumberQuote::class);

    }

    public function generateNumberQuote(){
        if($this->lastNumberQuote === [] || $this->lastNumberQuote === null){
            $this->firstNumberQuote = new GeneratorNumberQuote();

            $em = $this->doctrine->getManager();
            $em->persist($this->firstNumberQuote);
            $em->flush();

            $firstNumberQuote = $this->firstNumberQuote->getNumber();
            return $newNumberQuote = $firstNumberQuote+1;
        }else{
            $lastNumberQuote = $this->lastNumberQuote;
            return $newNumberQuote = $lastNumberQuote+1;
        }


    }

    public function getLastNumberQuote(){
        $lastNumberQuote = $this->repository->findLastNumberQuote();
        if($lastNumberQuote === [] || $lastNumberQuote === null){
             $this->lastNumberQuote = $lastNumberQuote;
             return 0;
        }else{
            return $this->lastNumberQuote = $lastNumberQuote[0]['number'];
        }

    }

    public function updateGeneratorNumberQuote($quote){
        //si le dernier numéro des devis  est plus grand que le numéro de devis
        if($this->lastNumberQuote < $quote->getNumberQuote()){
            //prend le numero du nouveau devis et le remplace par l'ancien
            $this->repository->updateLastNumberQuote($quote->getNumberQuote(),$this->lastNumberQuote);
        }
    }

    /**
     * @return mixed
     */
    public function getFirstNumberQuote()
    {
        return $this->firstNumberQuote;
    }
}