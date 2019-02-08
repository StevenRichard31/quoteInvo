<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 05/06/2018
 * Time: 16:20
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository
{


    public function findCustomerByName($customerName){


        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:Customer c WHERE c.name = :customerName'
            )
            ->setParameter('customerName',$customerName)
            ->getResult();
    }



    //recupère un client par sont nom si il existe
    public function findCustomerByName2($customerName){

        $sql = " 
            SELECT customer.name
            FROM customer
            WHERE customer.name = :customerName
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['customerName' => $customerName]);
        return $stmt->fetchAll();
    }

        public function findAllCustomer(){

            return $this->getEntityManager()
                ->createQuery(
                    'SELECT c,a,p FROM AppBundle:Customer c inner join c.address a inner join c.phones p'
                )
                ->getResult();
        }

    public function findCustomerByID($custumerId){

        $sql = " 
            select customer.id,customer.name,customer.mail,address.building,address.country,address.postal_code,address.street,address.town,phone.number,phone.type from customer
            inner join address on customer.address_id = address.id
            inner join phone on phone.customer_id = customer.id
            where customer.id = :custumerId;
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['custumerId' => $custumerId]);
        return $stmt->fetchAll();
    }

    public function findCustomerInvoiceQuote($customerId){

        return $this->getEntityManager()
            ->createQuery(
                'SELECT c,q,i FROM AppBundle:Customer c
                      left join c.quotes q
                      left join c.invoices i
                      where c.id = :customerId                    
                      '
            )
            ->setParameter('customerId', $customerId)
            ->getResult();
    }


}