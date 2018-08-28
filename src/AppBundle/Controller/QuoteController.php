<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/05/2018
 * Time: 17:56
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Quote;
use AppBundle\Form\RegistrationQuoteType;
use AppBundle\Form\SearchType;
use AppBundle\Manager\GeneratorNumberQuoteManager;
use AppBundle\Manager\QuoteManager;
use AppBundle\Manager\SearchManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuoteController
 * @Route("/quote")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
 */
class QuoteController extends Controller
{
    /**
     * @Route("/", name="quote.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexQuoteAction(Request $request)
    {
        $quotes = $this->get(QuoteManager::class)->getQuotesByLimit();

        $searchManager = $this->get(SearchManager::class);
        $search = $searchManager->create();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){

            $quotes = $searchManager->searchQuote($search);
        }

        return $this->render('@App/quote/index.html.twig', ["form" => $form->createView(), "results" => $quotes]);

    }

    /**
     * @Route("/form/{id}", name="quote.form", defaults={"id":null})
     * @param Quote|null $quote
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formQuoteAction(Quote $quote = null,Request $request)
    {
        //MANAGER
        //$generatorManager = $this->get(GeneratorNumberQuoteManager::class);
        $quoteManager = $this->get(QuoteManager::class);

        if ($quote === null) {
            //instanciation devis
            $quote = $quoteManager->create();
        }
        else {
            //si ce n'est pas un admin
            if (!$this->isGranted('edit', $quote)) {
                $this->addFlash('error', 'Vous n\'avez pas le droit de modifier ce devis !');
                return $this->redirectToRoute("quote.index");
            }
        }
        $quote = $quoteManager->getQuoteWithNumber($quote);

        //création du formulaire et création du lien avec l'objet
        $formQuote = $this->createForm(RegistrationQuoteType::class,$quote);
        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $formQuote->handleRequest($request);

        if($formQuote->isSubmitted() && $formQuote->isValid()) {
            //check les informations du devis
            $error = $quoteManager->checks($quote);
            if( $error != null){
                return $this->render('@App/quote/form.html.twig', ["form" => $formQuote->createView(), "error" => $error]);
            }

            $quoteManager->addQuote($quote);

            //retour sur la page index de "Quote"
            return $this->redirectToRoute("quote.index");
        }

        return $this->render('@App/quote/form.html.twig', ["form" => $formQuote->createView(), "error" =>  null]);

    }

    /**
     * @Route("/pdf/{id}", name="quote.pdf")
     */
    public function html2PdfAction($id = null,Request $request){
        $quoteManager = $this->get(QuoteManager::class);

        if ($id === null){
            return $this->redirectToRoute("quote.index");
        }
        else{
            $quote = $quoteManager->findQuoteById($id);
            if($quote === []){
                return $this->redirectToRoute("quote.index");
            }
        }

        //calcule des TVA et ajout aux documents
        $function = $this->container->get('utils.countFunction');
        $quote = $function->setLeftToPayAndTVA($quote);

        $quote = array_merge($quote, ["documentType" => 'DEVIS']);

        $template = $this->renderView('@App/pdf/pdf2.html.twig',["document" => $quote]);

        $html2pdf = $this->get('utils.html2Pdf');
        $html2pdf->create('P','A4', 'fr', true, 'UTF-8', array(8,10,8,10));
        return $html2pdf->generatePdf($template, "devis");
    }

    /**
     * @Route("/delete/{id}", name="quote.delete")
     * @IsGranted("ROLE_ADMIN")
     * @param Quote $quote
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteQuoteAction(Quote $quote){

        $this->get(QuoteManager::class)->delete($quote);
        return $this->redirectToRoute("quote.index");
    }



}