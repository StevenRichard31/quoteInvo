<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/05/2018
 * Time: 17:57
 */

namespace AppBundle\Controller;

use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Tva;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationPaymentMethodType;
use AppBundle\Form\RegistrationTvaType;
use AppBundle\Form\TvaType;
use AppBundle\Manager\PaymentMethodManager;
use AppBundle\Manager\TvaManager;
use AppBundle\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SettingController
 * @Route("/setting")
 * @IsGranted("ROLE_ADMIN")
 */
class SettingController extends Controller
{
    /**
     * @Route("/", name="setting.index")
     */
    public function indexSettingAction(Request $request)
    {
        return $this->render('@App/setting/index.html.twig');
    }

    /**
     * @Route("/tva/form", name="setting.tva.form")
     */
    public function formTvaAction(Request $request)
    {
        $manager = $this->get(TvaManager::class);
        $tva = $manager->create();

        //création du formulaire et création du lien avec l'objet
        $form = $this->createForm(RegistrationTvaType::class,$tva);
        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->add($tva);
            //retour sur la page index de "Setting"
            return $this->redirectToRoute("setting.index");
        }

        return $this->render('@App/setting/tva/form.html.twig', ["form" => $form->createView(),"tva" => $tva]);
    }

    /**
     * @Route("/paymentMethod/form", name="setting.paymentMethod.form")
     */
    public function formPaymentMethodAction(Request $request)
    {
        $manager = $this->get(PaymentMethodManager::class);
        $paymentM = $manager->create();;

        //création du formulaire et création du lien avec l'objet
        $form = $this->createForm(RegistrationPaymentMethodType::class,$paymentM);
        //hydrate l'objet avec les valeurs entrées dans le formulaire par l'utilisateur
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->add($paymentM);
            //retour sur la page index de "Setting"
            return $this->redirectToRoute("setting.index");
        }

        return $this->render('@App/setting/paymentMethod/form.html.twig', ["form" => $form->createView(),"paymentM" => $paymentM]);
    }

    /**
     * @Route("/user", name="setting.user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexUserAction(Request $request)
    {
        $users = $this->get(UserManager::class)->getUsers();
        return $this->render('@App/setting/user/index.html.twig' ,['users' => $users]);
    }

    /**
     * @Route("/user/enabled/{id}", name="setting.user.enabled")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function userEnabledAction($id , Request $request){
        $manager = $this->get(UserManager::class);
        $user = $manager->getUserById($id);
        //change l'état d'activation de l'utilisateur
        $manager->switchEnabled($user);

        return $this->redirectToRoute("setting.user");

    }

    /**
     * @Route("/user/delete/{id}", name="setting.user.delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id){
        $manager = $this->get(UserManager::class);
        $user = $manager->getUserById($id);
        $manager->deleteUser($user);

        return $this->redirectToRoute("setting.user");
    }
}