<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 27/07/2018
 * Time: 08:28
 */

namespace AppBundle\Manager;

use AppBundle\Entity\PaymentMethod;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;


class PaymentMethodManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * PaymentMethodManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(){
        return new PaymentMethod();
    }

    public function add($paymentM){
        //si l'information est valide on perste l'information
        $em = $this->doctrine->getManager();
        //persistance ne marche que sur des objets vide, ne creer pas d'erreur pour autant
        $em->persist($paymentM);
        $em->flush();
    }
}