<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 27/07/2018
 * Time: 07:54
 */

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;



class UserManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * UserManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getUsers()
    {
        return $this->doctrine->getRepository(User::class)->findAll();
    }

    public function getUserById($id){
        return $this->doctrine->getRepository(User::class)->find($id);
    }

    public function switchEnabled($user){
        if($user !== null){
            $state = $user->isEnabled();

            if($state == false){
                $user->setEnabled(true);
            }
            else{
                $user->setEnabled(false);
            }
            $em = $this->doctrine->getManager();
            $em->flush();
        }
    }

    public function deleteUser($user){
        if($user !== null){
            //supression de l'objet
            $em= $this->doctrine->getManager();
            $em->remove($user); // DELETE FROM user WHERE id = ? (? = user.id)
            $em->flush();
        }
    }
}