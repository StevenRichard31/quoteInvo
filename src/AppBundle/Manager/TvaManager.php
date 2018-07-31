<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 27/07/2018
 * Time: 08:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Tva;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;

class TvaManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * TvaManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function create(){
        return new Tva();
    }

    public function add($tva){
        //si l'information est valide on perste l'information
        $em = $this->doctrine->getManager();
        $em->persist($tva);
        $em->flush();
    }
}