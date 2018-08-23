<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/07/2018
 * Time: 20:10
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Invoice;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;


class InvoiceManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    private $repository;

    private $isNewInvoice = false;

    private $initialNumberInvoice;

    private $originalProducts;

    private $error = null;




    /**
     * InvoiceManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(Invoice::class);
    }

    public function getInvoiceByLimit(){
        return $this->repository->findAllInvoiceByLimit();
    }
    public function findInvoicesNotPaid(){
        return $this->repository->findInvoicesNotPaid();
    }

    public function create(){
        $this->isNewInvoice = true;
        return new Invoice();
    }

    public function getLastNumberInvoice(){
        return $this->repository->findLastNumberInvoice();
    }

    public function getAllNumberInvoice(){
        return $this->repository->findAllNumberInvoice();
    }

    public function invoicePaid($id){
        $this->repository->invoicePaid($id);
    }

    public function setNumberInvoice($invoice){
        $lastNumberInvoice = $this->getLastNumberInvoice();
        if($this->isNewInvoice){
            if($lastNumberInvoice !== null || $lastNumberInvoice !== []){
                //incrémentation de '1' et insertion du numéros vers la nouvelle facture
                $invoice->setNumberInvoice($lastNumberInvoice[0]['max(number_invoice)']+1);
            }
        }
        else{
            $this->initialNumberInvoice = $invoice->getNumberInvoice();
        }
        return $invoice;
    }

    public function setOriginalProducts($invoice){
        $originalProducts = new ArrayCollection();
        foreach ($invoice->getProducts() as $product){
            $originalProducts->add($product);
        }
        $this->originalProducts = $originalProducts;
    }

    public function checks($invoice){
        $lastNumberInvoice = $this->getLastNumberInvoice();
        $listNumberInvoice = $this->getAllNumberInvoice();
        if( $this->isNewInvoice){
            if( $listNumberInvoice != [] || $listNumberInvoice != null) {
                for ($i = 0; $i < count($listNumberInvoice[0]); $i++) {
                    if ($listNumberInvoice[$i]['number_invoice'] == $invoice->getNumberInvoice()) {
                        $this->error = 'Le numéro de la facture existe déjà';
                    }
                }
            }
            //verifie si le numero de la facture insérer est plus petit ou égal au dernier plus grand insérer dans la bdd
            if($lastNumberInvoice != null && $invoice->getNumberInvoice()<= $lastNumberInvoice[0]['max(number_invoice)']){
                $this->error = 'Le numéro de la facture est trop petit ';
            }
        }
        else{
            //verifie si le numero de la facture à changer
            if($this->initialNumberInvoice != $invoice->getNumberInvoice()){
                $this->error = 'Le numéro de la facture à été modifier';
            }
        }

        //si pas de produit ajouter au devis
        if ($invoice->getProducts()->isEmpty()){
            $this->error = 'Ajouter au moins un produit';
        }
        return $this->error;
    }

    public function updateInvoiceProducts($invoice){
        $em =$this->doctrine->getManager();
        foreach ($this->originalProducts as $product){
            //si un produit à été supprimer de la collection , alors supprime la en BDD
            if( $invoice->getProducts()->contains($product) === false){
                $em->remove($product);
                $em->flush();
            }
        }
    }

    /**
     * @param Invoice $invoice
     */
    public function add(Invoice $invoice){
        $em = $this->doctrine->getManager();
        $em->persist($invoice);
        $em->flush();
    }

    public function hydrateInvoice($quote,$invoice){
        //on hydrate la facture avec les info devis
        $invoice
            ->setPaymentMethod($quote->getPaymentMethod())
            ->setPercentageAdvencePayment($quote->getPercentageAdvencePayment())
            ->setDocumentName($quote->getDocumentName())
            ->setDocumentName($quote->getDocumentName())
            ->setQuote($quote);
        foreach ($quote->getProducts() as $productQuote){
            $productInvoice = clone $productQuote;
            $invoice->addProduct($productInvoice);
        }
        return $invoice;
    }

    public function findInvoiceByID($id){
        return $this->repository->findInvoiceByID($id);
    }
}