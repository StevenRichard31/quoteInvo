<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 26/06/2018
 * Time: 15:51
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Search;
use Doctrine\ORM\EntityRepository;
use PDO;

class InvoiceRepository extends EntityRepository
{
    public function findLastNumberInvoice(){

        $sql = "SELECT max(number_invoice) FROM invoice";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
/*
    public function findInvoiceByID($invoiceId){

        $sql = "select *,invoice.id as documentID, customer.name as customerName, tva.name as tvaName, payment_method.name as paymentMethodName, product.name as productName, number_invoice as numberDocument from invoice
                inner join customer on customer.id = customer_id
                inner join address on address_id = address.id
                inner join tva on tva_id = tva.id 
                inner join payment_method on payment_method_id = payment_method.id
                inner join invoice_product on  invoice_id = invoice.id
                inner join product on product_id = product.id
                where invoice.id = :invoiceId";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['invoiceId' => $invoiceId]);
        return $stmt->fetchAll();
    }
*/
    public function findInvoiceByID($invoiceId){

        return $this->getEntityManager()
            ->createQuery(
                'SELECT i,c,a,p,pro,t FROM AppBundle:Invoice i 
                      inner join i.customer c 
                      inner join c.address a
                      inner join i.paymentMethod p
                      inner join i.products pro
                      inner join pro.tva t
                      where i.id = :invoiceId'
            )
            ->setParameter('invoiceId',$invoiceId)
            ->getResult();
    }

    public function findAllBySearch(Search $search){

        $sql = "select *,invoice.id as invoiceID from invoice
                inner join customer on customer_id = customer.id
                where customer.name like :keyword
                or  invoice.number_invoice like :keyword
                or document_name like :keyword
                order by invoice.number_invoice DESC ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['keyword' => "%" . $search->getKeyword() . "%"]);
        return $stmt->fetchAll();
    }

    public function findAllNumberInvoice(){

        $sql = "select number_invoice from invoice";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllInvoiceByLimit($limitMax = 20){


        $sql = "select *,invoice.id as invoiceID from invoice
                inner join customer on customer_id = customer.id
                order by invoice.number_invoice DESC
                LIMIT :limitMax";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        //permet de lier un parametre à une variable et de typer sont contenue
        $stmt->bindParam('limitMax', $limitMax, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}