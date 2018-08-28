<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/07/2018
 * Time: 11:18
 */

namespace AppBundle\Manager;

use AppBundle\AppBundleEvents;
use AppBundle\Entity\Quote;
use AppBundle\Event\QuoteEvent;
use Components\Utils\CountFunction;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QuoteManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    private $repository;

    private $isNewQuote = false;

    private $listQuotesNumbers;

    private $initialNumberQuote;

    private $lastNumberQuote;

    private $originalProducts;

    private $newNumberQuote;

    private $error = null;

    private $generatorManager;

    private $utilsCountFunction;

    private $dispatcher;

    /**
     * QuoteManager constructor.
     * @param Doctrine $doctrine
     * @param EventDispatcherInterface $dispatcher
     * @param CountFunction $utilsCountFunction
     * @param GeneratorNumberQuoteManager $generatorManager
     */
    public function __construct(CountFunction $utilsCountFunction, GeneratorNumberQuoteManager $generatorManager, Doctrine $doctrine,EventDispatcherInterface $dispatcher)
    {
        $this->doctrine = $doctrine;
        $this->dispatcher = $dispatcher;
        $this->repository = $this->doctrine->getRepository(Quote::class);
        $this->utilsCountFunction = $utilsCountFunction;
        $this->generatorManager = $generatorManager;
    }

    public function create(){
        $this->isNewQuote = true;
        return new Quote();
    }

    public function addQuote($quote){
        ///
        //$quote = $event->getQuote();
        //$quoteManager = $event->getQuoteManager();
        $generatorManager = $this->generatorManager;

        //verifie si la collection de produit à changer
        $this->updateQuoteProducts($quote);
        //calcule des montant et ajout aux documents
        $this->utilsCountFunction->setAllCount($quote);

        //ajoute du devis en BDD
        $this->add($quote);
        if($this->isNewQuote()){
            $generatorManager->updateGeneratorNumberQuote($quote);
        }

        $event = new QuoteEvent($quote);
        $this->dispatcher->dispatch(AppBundleEvents::ADD_QUOTE,$event);
    }

    public function getQuoteWithNumber($quote){
        //récupération la liste des numeros de devis existant
        $this->getQuotesNumbers();
        //récupération du dernier numéro de devis générer
        $this->setLastNumberQuote($this->generatorManager->getLastNumberQuote());
        // si c'est un nouveau devis
        if($this->isNewQuote()){
            $newNumberQuote = $this->generatorManager->generateNumberQuote();
            $this->setNewNumberQuote($newNumberQuote);
            $quote->setNumberQuote($newNumberQuote);
        }
        else{
            $this->setInitialNumberQuote($quote->getNumberQuote());
        }
        // création d'une collection de produit existant
        $this->setOriginalProducts($quote);
        return $quote;
    }

    public function getQuotesByLimit(){
        return $this->repository->findAllQuoteByLimit();
    }

    public function getQuotesNumbers(){
        return $this->listQuotesNumbers = $this->repository->findAllQuotesNumbers();
    }

    public function setOriginalProducts($quote){
        $originalProducts = new ArrayCollection();
        foreach ($quote->getProducts() as $product){
            $originalProducts->add($product);
        }
        $this->originalProducts = $originalProducts;
    }

    public function checks($quote){

        //si nouveau devis
        if( $this->isNewQuote()){
            //si il existe des numeros de devis
            if( $this->listQuotesNumbers != [] || $this->listQuotesNumbers != null){
                //on verifie le numeros du devis créé si il existe déjà en BDD
                if(in_array($quote->getNumberQuote(),$this->listQuotesNumbers)){
                    $this->error = 'Le numéro du devis existe déjà';
                }
            }
            //si le numero du devis insérer est plus petit que le numero proposer par le "generatorNumberQuote"
            if( $quote->getNumberQuote()< $this->newNumberQuote){
                $this->error = 'Le numéro du devis est trop petit ';
            }
        }
        else{
            //verifie si le numero du devis à changer
            if($this->initialNumberQuote != $quote->getNumberQuote()){
                $this->error = 'Le numéro du devis à été modifier';
            }
        }

        //si pas de produit ajouter au devis
        if ($quote->getProducts()->isEmpty()){
            $this->error = 'Ajouter au moins un produit';
        }

        return $this->error;
    }

    public function updateQuoteProducts($quote){
        $em = $this->doctrine->getManager();

        foreach ($this->originalProducts as $product){

            //si un produit à été suprimer de la collection , alors suprime la en BDD
            if(false === $quote->getProducts()->contains($product)){
                $em->remove($product);
                $em->flush();
            }
        }
    }

    public function findQuotesWaiting(){
        return $this->repository->findQuotesWaiting();
    }

    /**
     * @param Quote $quote
     */
    public function add(Quote $quote){
        $em = $this->doctrine->getManager();
        $em->persist($quote);
        $em->flush();
    }

    public function findQuoteById($id){
        return $this->repository->findQuoteById($id);
    }

    /**
     * @param Quote $quote
     */
    public function delete(Quote $quote){
        if($quote !== null){
            //supression de l'objet
            $em = $this->doctrine->getManager();
            $em->remove($quote); // DELETE FROM quote WHERE id = ? (? = quote.id)
            $em->flush();
        }
    }

    public function find($idQuote){
        return $this->repository->find($idQuote);
    }

    /**
     * @return bool
     */
    public function isNewQuote()
    {
        return $this->isNewQuote;
    }

    /**
     * @param mixed $initialNumberQuote
     */
    public function setInitialNumberQuote($initialNumberQuote)
    {
        $this->initialNumberQuote = $initialNumberQuote;
    }

    /**
     * @param mixed $lastNumberQuote
     */
    public function setLastNumberQuote($lastNumberQuote)
    {
        $this->lastNumberQuote = $lastNumberQuote;
    }

    /**
     * @param mixed $newNumberQuote
     */
    public function setNewNumberQuote($newNumberQuote)
    {
        $this->newNumberQuote = $newNumberQuote;
    }


}