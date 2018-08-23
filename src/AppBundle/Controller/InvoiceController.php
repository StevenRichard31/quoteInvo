<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/05/2018
 * Time: 17:54
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Quote;
use AppBundle\Entity\Search;
use AppBundle\Form\RegistrationInvoiceType;
use AppBundle\Form\SearchType;
use AppBundle\Manager\InvoiceManager;
use AppBundle\Manager\QuoteManager;
use AppBundle\Manager\SearchManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InvoiceController
 * @Route("/invoice")
 * @IsGranted("ROLE_ADMIN")
 */
class InvoiceController extends Controller
{
    /**
     * @Route("/", name="invoice.index")
     */
    public function indexInvoiceAction(Request $request)
    {
        $invoices = $this->get(InvoiceManager::class)->getInvoiceByLimit();

        $searchManager = $this->get(SearchManager::class);
        $search = $searchManager->create();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){

            $invoices = $searchManager->searchInvoice($search);
        }

        return $this->render('@App/invoice/index.html.twig', ["form" => $form->createView(), "results" => $invoices]);


    }


    /**
     * @Route("/form/{id}", name="invoice.form", defaults={"id":null})
     * @param Invoice|null $invoice
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formInvoiceAction(Invoice $invoice = null, Request $request)
    {
        $invoiceManager = $this->get(InvoiceManager::class);
        if($invoice === null ){
            //nouvelle facture sans devis
            $invoice = $invoiceManager->create();

        }
        //on génère et donne un numero de facture
        $invoice = $invoiceManager->setNumberInvoice($invoice);

        // création d'une collection de produit existant
        $invoiceManager->setOriginalProducts($invoice);

        //création du formulaire et création du lien avec l'objet
        $formInvoice = $this->createForm(RegistrationInvoiceType::class,$invoice);

        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $formInvoice->handleRequest($request);

        if($formInvoice->isSubmitted() && $formInvoice->isValid()) {
            //check les informations de la facture
            $error = $invoiceManager->checks($invoice);
            if( $error != null){
                return $this->render('@App/invoice/form.html.twig', ["form" => $formInvoice->createView(), "error" => $error]);
            }
            //verifie si la collection de produit à changer
            $invoiceManager->updateInvoiceProducts($invoice);

            //calcule des montant et ajout aux documents
            $function = $this->container->get('utils.countFunction');
            $function->setAllCount($invoice);

            //si l'information est valide on persiste l'information
            $invoiceManager->add($invoice);

            //retour sur la page index de "Invoice"
            return $this->redirectToRoute("invoice.index");
        }

        return $this->render('@App/invoice/form.html.twig', ["form" => $formInvoice->createView()]);


    }

    /**
     * @Route("/formWithQuote/{idQuote}", name="invoice.form.withQuote")
     * @param null $idQuote
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formInvoiceWithQuoteAction($idQuote = null, Request $request)
    {
        //MANAGER
        $quoteManager = $this->get(QuoteManager::class);
        $invoiceManager = $this->get(InvoiceManager::class);
        if ($idQuote !== null){
            //nouvelle facture avec info devis
            $invoice = $invoiceManager->create();

            //recuperation du devis
            $quote = $quoteManager->find($idQuote);

            //si devis n'existe pas OU si devis à déjà une facture lier
            if($quote == null || $quote->getInvoice() !== null){
                return $this->redirectToRoute("quote.index");
            }

            //lien devis facture
            $quote->setInvoice($invoice);

            //on hydrate la facture avec les info devis
            $invoiceManager->hydrateInvoice($quote,$invoice);
        }
        else{
            return $this->redirectToRoute("quote.index");
        }

        //on génère et donne un numero de facture
        $invoice = $invoiceManager->setNumberInvoice($invoice);

        // création d'une collection de produit existant
        $invoiceManager->setOriginalProducts($invoice);

        //création du formulaire et création du lien avec l'objet
        $formInvoice = $this->createForm(RegistrationInvoiceType::class,$invoice);

        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $formInvoice->handleRequest($request);

        if($formInvoice->isSubmitted() && $formInvoice->isValid()) {

            //check les informations de la facture
            $error = $invoiceManager->checks($invoice);
            if( $error != null){
                return $this->render('@App/invoice/form.html.twig', ["form" => $formInvoice->createView(), "error" => $error]);
            }
            //verifie si la collection de produit à changer
            $invoiceManager->updateInvoiceProducts($invoice);

            //calcule des montant et ajout aux documents
            $function = $this->container->get('utils.countFunction');
            $function->setAllCount($invoice);

            //si l'information est valide on persiste l'information
            $invoiceManager->add($invoice);

            //retour sur la page index de "Invoice"
            return $this->redirectToRoute("invoice.index");
        }

        return $this->render('@App/invoice/form.html.twig', ["form" => $formInvoice->createView()]);

    }

    /**
     * @Route("/pdf/{id}", name="invoice.pdf")
     */
    public function html2PdfAction($id = null,Request $request){
        $invoiceManager = $this->get(InvoiceManager::class);
        if ($id === null){
            return $this->redirectToRoute("invoice.index");
        }
        else{
            $invoice = $invoiceManager->findInvoiceByID($id);

            if($invoice === []){
                return $this->redirectToRoute("invoice.index");
            }
        }

        //calcule des TVA et ajout aux documents
        $function = $this->container->get('utils.countFunction');
        $invoice = $function->setTVA($invoice);
        $invoice = $function->setLeftToPay($invoice);

        $invoice = array_merge($invoice, ["documentType" => 'FACTURE']);

        $template = $this->renderView('@App/pdf/pdf2.html.twig',["document" => $invoice]);

        $html2pdf = $this->get('utils.html2Pdf');
        $html2pdf->create('P','A4', 'fr', true, 'UTF-8', array(8,10,8,10));
        return $html2pdf->generatePdf($template, "facture");
    }

    /**
     * @param null $id
     * @Route("paid/{id}", name="invoice.paid")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function invoicePaid($id = null){
        if($id !== null){
            $this->get(InvoiceManager::class)->invoicePaid($id);
        }
        return $this->redirectToRoute("homepage");

    }


}