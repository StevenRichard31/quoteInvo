<?php

namespace AppBundle\Controller;

use AppBundle\Manager\InvoiceManager;
use AppBundle\Manager\QuoteManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $quotes = $this->get(QuoteManager::class)->findQuotesWaiting();
        $invoices = $this->get(InvoiceManager::class)->findInvoicesNotPaid();

        return $this->render('@App/index.html.twig', [ "quotes" => $quotes,"invoices" => $invoices]);
    }


}
