<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 07/09/2018
 * Time: 15:26
 */

namespace AppBundle\Event;


use AppBundle\Entity\Customer;
use Symfony\Component\EventDispatcher\Event;

class CustomerEvent extends Event
{

    private $customer;

    /**
     * CustomerEvent constructor.
     * @param $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

}