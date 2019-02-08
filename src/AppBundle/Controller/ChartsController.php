<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/08/2018
 * Time: 12:11
 */

namespace AppBundle\Controller;


use AppBundle\Entity\SearchCharts;
use AppBundle\Form\SearchChartsType;
use AppBundle\Manager\InvoiceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController
 * @Route("/charts")
 * @IsGranted("ROLE_ADMIN")
 */
class ChartsController extends Controller
{
    /**
     * @Route("/", name="charts.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexCustomerAction( Request $request)
    {

        $searchCharts = new SearchCharts();

        $allInvoices = $this->get(InvoiceManager::class)->findInvoices($searchCharts);

        $moisList = $this->getNumberInvoice($allInvoices);

        $form = $this->createForm(SearchChartsType::class,$searchCharts);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $allInvoices = $this->get(InvoiceManager::class)->findInvoices($searchCharts);

            $moisList = $this->getNumberInvoice($allInvoices);

            $form = $this->createForm(SearchChartsType::class,$searchCharts);
            $form->handleRequest($request);
            return $this->render('@App/charts/index.html.twig',["form" => $form->createView(), "allInvoices" => $allInvoices,"moisList" => $moisList]);

        }

        return $this->render('@App/charts/index.html.twig',["form" => $form->createView(), "allInvoices" => $allInvoices,"moisList" => $moisList]);
    }

    private function getNumberInvoice($allInvoices){
        $moisList = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0];
        foreach ($allInvoices as $invoice){
            $mois = intval($invoice->getCreationDate()->format('m'));
            //dump($mois);
            //dump($invoice);
            $total = $invoice->getTotalIncludingTaxes();
            //injection de la data
            $moisList[$mois] = $moisList[$mois]+$total ;


        }
        //die();
        return $moisList;
    }
}