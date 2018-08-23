<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 14/06/2018
 * Time: 11:25
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Search;
use Doctrine\ORM\EntityRepository;
use PDO;

class QuoteRepository extends EntityRepository
{

    public function findLastNumberQuote(){

        $sql = "SELECT max(number_quote) FROM quote";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findQuoteById($quoteId){

        return $this->getEntityManager()
            ->createQuery(
                'SELECT q,c,a,p,pro,t FROM AppBundle:Quote q
                      inner join q.customer c 
                      inner join c.address a
                      inner join q.paymentMethod p
                      inner join q.products pro
                      inner join pro.tva t
                      where q.id = :quoteId'
            )
            ->setParameter('quoteId',$quoteId)
            ->getResult();
    }

    /**
     * @param Search $search
     * @return array
     */
    public function findAllBySearch(Search $search){

        $sql = "select *,quote.id as quoteID from quote
                inner join customer on customer_id = customer.id
                where customer.name like :keyword
                or  quote.number_quote like :keyword
                or document_name like :keyword
                order by quote.number_quote DESC ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['keyword' => "%" . $search->getKeyword() . "%"]);
        return $stmt->fetchAll();
    }

    public function findAllQuotesNumbers(){

        $sql = "select number_quote from quote";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllQuoteByLimit($limitMax = 30){

        $sql = "select *,quote.id as quoteID from quote
                inner join customer on customer_id = customer.id
                order by quote.number_quote DESC
                LIMIT :limitMax";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        //permet de lier un parametre à une variable et de typer sont contenue
        $stmt->bindParam('limitMax', $limitMax, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //récupération des devis qui n'ont pas générés de factures et sont encore valide
    public function findQuotesWaiting(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT q,c FROM AppBundle:Quote q
                      inner join q.customer c
                      where :now <= q.validationDeadline
                      and q.invoice is null
                      order by q.validationDeadline 
                      '
            )
            ->setParameter('now',new \DateTime('now'))
            ->getResult();
    }


}