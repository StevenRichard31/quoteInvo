<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 25/07/2018
 * Time: 08:04
 */

namespace AppBundle\Manager;


use AppBundle\Controller\Exception\CustomerHaveDocumentException;
use AppBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;



class CustomerManager
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    private $newCustomer = false;

    private $customerNameInitial;

    private $customer;

    private $dispatcher;


    /**
     * CustomerManager constructor.
     * @param Doctrine $doctrine
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(Doctrine $doctrine,EventDispatcherInterface $dispatcher)
    {
        $this->doctrine = $doctrine;
        $this->dispatcher = $dispatcher;
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

    //vérifie si customer a au moins un document
    public function isCustomerWithDocument($customerId){

        $repository = $this->doctrine->getRepository(Customer::class);
        $array = $repository->findCustomerInvoiceQuote($customerId);

        if($array === []){
            return ;
        }else{
            $customer = $array[0];
            $quotes = $customer->getQuotes();
            $invoices = $customer->getInvoices();
            $numberQuotes = $quotes->count();
            $numberInvoices = $invoices->count();

            if($numberQuotes > 0 || $numberInvoices > 0){
                throw new CustomerHaveDocumentException("Le client : ".$customer->getName().", ne peux être supprimer tant qu'il à des documents associés.", $code = 510) ;
            }else{
                return ;
            }

        }
    }


    public function getCustomers(){

        $repository = $this->doctrine->getRepository(Customer::class);
        return $repository->findAllCustomer();

    }

    //persiste le client en BDD
    /**
     * @param Customer $customer
     */
    public function add(Customer $customer){
        $em = $this->doctrine->getManager();
        $em->persist($customer);
        $em->flush();
    }

    //vérifie si le client exist en BDD par le NOM
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