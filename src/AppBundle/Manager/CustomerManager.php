<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 25/07/2018
 * Time: 08:04
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;


class CustomerManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    private $newCustomer = false;

    private $customerNameInitial;

    private $customer;



    /**
     * CustomerManager constructor.
     * @param Doctrine $doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    public function create(){
        $this->newCustomer = true;
        return new Customer();

    }

    public function updateCustomerPhone($originalPhones,$customer){
        $em = $this->doctrine->getManager();

        foreach ($originalPhones as $phone){

            if( $customer->getPhones()->contains($phone) === false ){
                $em->remove($phone);
                $em->flush();
            }
        }

    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getOriginalPhones(Customer $customer)
    {

        $originalPhones = new ArrayCollection();
        foreach ($customer->getPhones() as $phone){
            $originalPhones->add($phone);
        }

        return $originalPhones;
    }


    public function getCustomers(){

        $repository = $this->doctrine->getRepository(Customer::class);
        return $repository->findAllCustomer();

    }

    /**
     * @param Customer $customer
     */
    public function add(Customer $customer){
        $em = $this->doctrine->getManager();
        $em->persist($customer);
        $em->flush();
    }

    public function isExist(Customer $customer){
        $res = $this->doctrine
                    ->getRepository(Customer::class)
                    ->findCustomerByName2($customer->getName());
        if($res == []){
            return false;
        }else{
            return true;
        }
    }

    public function checkName($customer){
        if($this->newCustomer == true){
            if($this->isExist($customer)){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            if(($this->getCustomerNameInitial() != $customer->getName()) && $this->isExist($customer) != []){
                return false;
            }
            else{
                return true;
            }
        }
    }

    /**
     * @param Customer $customer
     */
    public function delete(Customer $customer){
        if($customer !== null){
            //supression de l'objet
            $em = $this->doctrine->getManager();
            $em->remove($customer); // DELETE FROM customer WHERE id = ? (? = customer.id)
            $em->flush();
        }
    }

/*--------------------------------------------------------------*/


    /**
     * @return mixed
     */
    public function getCustomerNameInitial()
    {
        return $this->customerNameInitial;
    }

    /**
     * @param mixed $customerNameInitial
     */
    public function setCustomerNameInitial($customerNameInitial)
    {
        $this->customerNameInitial = $customerNameInitial->getName();;
    }

    /**
     * @return null
     */
    public function isNewCustomer()
    {
        return $this->newCustomer;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }






}