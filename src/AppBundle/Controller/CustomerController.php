<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/05/2018
 * Time: 17:42
 */

namespace AppBundle\Controller;

use AppBundle\Manager\CustomerManager;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Entity\Customer;
use AppBundle\Form\RegistrationCustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController
 * @Route("/customer")
 * @IsGranted("ROLE_ADMIN")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/", name="customer.index")
     *
     */
    public function indexCustomerAction(Request $request)
    {
        $customers = $this->get(CustomerManager::class)->getCustomers();
        return $this->render('@App/customer/index.html.twig', ["customers" => $customers]);
    }

    /**
     * @Route("/form/{id}", name="customer.form", defaults={"id":null})
     * @param Customer|null $customer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function customerFormAction(Customer $customer = null,Request $request)
    {
        $manager = $this->get(CustomerManager::class);

        if ($customer === null){
            $customer = $manager->create();
        }

        //recuperation du nom du client du formulaire
        $manager->setCustomerNameInitial($customer);
        //création de la liste des "phones"
        $originalPhones = $manager->getOriginalPhones($customer);

        //création du formulaire et création du lien avec l'objet
        $form = $this->createForm(RegistrationCustomerType::class,$customer);
        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $form->handleRequest($request);

        //regarde les regle de validation(form->isValid) appel les composants de validation
        if($form->isSubmitted() && $form->isValid()){

            if($manager->checkName($customer)== false){
                //message d'erreur
                $errorName = 'Le nom de ce client est déjà utiliser';
                return $this->render('@App/customer/form.html.twig', ["form" => $form->createView(), "errorName" => $errorName ]);
            };

            //on supprime les numeros qui ne sont plus utiliser
            $manager->updateCustomerPhone($originalPhones,$customer);
            //si l'information est valide on persiste l'information
            $manager->add($customer);

            //retour sur la page index de "Client"
            return $this->redirectToRoute("customer.index");
        }

        //on passe la view du formulaire à la "VUE" => form.html.twig
        return $this->render('@App/customer/form.html.twig', ["form" => $form->createView(), "errorName" => null ]);

    }


    /**
     * @Route("/delete/{id}", name="customer.delete")
     * @param Customer $customer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCustomerAction(Customer $customer){

        $customerManager = $this->get(CustomerManager::class);

        $customerManager->isCustomerWithDocument($customer->getId());




        $customerManager->delete($customer);
        return $this->redirectToRoute("customer.index");
    }


}

