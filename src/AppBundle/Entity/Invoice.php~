<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 31/05/2018
 * Time: 11:11
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Invoice
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\NotNull(message="Le champ 'Numéro de facture' n'est pas rempli")
     * @Assert\Range(min="170000",minMessage="Le 'Numéro de facture' doit commencer par 170000 minimum.")
     * @Assert\Type("integer")
     */
    private $numberInvoice;


    //We can search this "INVOICE" by "document_name" !
    /**
     * @ORM\Column(type="string",length=100,nullable=true)
     * @Assert\Length(max="100",maxMessage="Le champ 'Nom de dossier' ne doit pas dépasser 100 caratères.")
     * @Assert\Type("string")
     */
    private $documentName;


    //Creation date of invoice
    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $creationDate;


    //Billing date of invoice
    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $billingDate;


    //the customer who requested the invoice
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="invoices")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Sélectionner un client.")
     */
    private $customer;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PaymentMethod", inversedBy="invoices")
     * @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id", nullable=true)
     * @Assert\NotNull(message="Sélectionner un moyen de paiement.")
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("integer")
     * @Assert\Range(max="99", maxMessage="Vous ne pouvez pas faire un acompte de plus de 99%")
     */
    private $percentageAdvencePayment;




    //allows to know from which quote this invoice comes
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Quote", mappedBy="invoice")
     */
    private $quote;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="invoice",cascade={"persist"})
     * @Assert\NotNull(message="Ajouter un produit")
     * @Assert\Valid(traverse=true)
     */
    private $products;


    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $paid;

    /**
     * @ORM\Column(type="float", scale=2,nullable=true)
     */
    private $totalExcludingTaxes;
    /**
     * @ORM\Column(type="float", scale=2,nullable=true)
     */
    private $sumTaxes;
    /**
     * @ORM\Column(type="float", scale=2,nullable=true)
     */
    private $totalIncludingTaxes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paid = false;

        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->id === null){
            /*date now*/
            $dateNow = new \DateTime("now");
            /*date 1 month later*/
            $date1MonthLater = new \DateTime("now");

            $this->creationDate = $dateNow;
            $this->billingDate = $date1MonthLater ->modify('+1 month');
        }
    }

    public function __toString() {
        return $this->numberInvoice;
    }

    /*----------------------------------------------------------------------------------------------------------------*/

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numberInvoice
     *
     * @param integer $numberInvoice
     *
     * @return Invoice
     */
    public function setNumberInvoice($numberInvoice)
    {
        $this->numberInvoice = $numberInvoice;

        return $this;
    }

    /**
     * Get numberInvoice
     *
     * @return integer
     */
    public function getNumberInvoice()
    {
        return $this->numberInvoice;
    }

    /**
     * Set documentName
     *
     * @param string $documentName
     *
     * @return Invoice
     */
    public function setDocumentName($documentName)
    {
        $this->documentName = $documentName;

        return $this;
    }

    /**
     * Get documentName
     *
     * @return string
     */
    public function getDocumentName()
    {
        return $this->documentName;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Invoice
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set billingDate
     *
     * @param \DateTime $billingDate
     *
     * @return Invoice
     */
    public function setBillingDate($billingDate)
    {
        $this->billingDate = $billingDate;

        return $this;
    }

    /**
     * Get billingDate
     *
     * @return \DateTime
     */
    public function getBillingDate()
    {
        return $this->billingDate;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Invoice
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set paymentMethod
     *
     * @param \AppBundle\Entity\PaymentMethod $paymentMethod
     *
     * @return Invoice
     */
    public function setPaymentMethod(\AppBundle\Entity\PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return \AppBundle\Entity\PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }


    /**
     * Set quote
     *
     * @param \AppBundle\Entity\Quote $quote
     *
     * @return Invoice
     */
    public function setQuote(\AppBundle\Entity\Quote $quote = null)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote
     *
     * @return \AppBundle\Entity\Quote
     */
    public function getQuote()
    {
        return $this->quote;
    }



    /**
     * Set percentageAdvencePayment
     *
     * @param integer $percentageAdvencePayment
     *
     * @return Invoice
     */
    public function setPercentageAdvencePayment($percentageAdvencePayment)
    {
        $this->percentageAdvencePayment = $percentageAdvencePayment;

        return $this;
    }

    /**
     * Get percentageAdvencePayment
     *
     * @return integer
     */
    public function getPercentageAdvencePayment()
    {
        return $this->percentageAdvencePayment;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return Invoice
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Invoice
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $product->setInvoice($this);
        $this->products->add($product);

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set totalExcludingTaxes
     *
     * @param float $totalExcludingTaxes
     *
     * @return Invoice
     */
    public function setTotalExcludingTaxes($totalExcludingTaxes)
    {
        $this->totalExcludingTaxes = $totalExcludingTaxes;

        return $this;
    }

    /**
     * Get totalExcludingTaxes
     *
     * @return float
     */
    public function getTotalExcludingTaxes()
    {
        return $this->totalExcludingTaxes;
    }

    /**
     * Set sumTaxes
     *
     * @param float $sumTaxes
     *
     * @return Invoice
     */
    public function setSumTaxes($sumTaxes)
    {
        $this->sumTaxes = $sumTaxes;

        return $this;
    }

    /**
     * Get sumTaxes
     *
     * @return float
     */
    public function getSumTaxes()
    {
        return $this->sumTaxes;
    }

    /**
     * Set totalIncludingTaxes
     *
     * @param float $totalIncludingTaxes
     *
     * @return Invoice
     */
    public function setTotalIncludingTaxes($totalIncludingTaxes)
    {
        $this->totalIncludingTaxes = $totalIncludingTaxes;

        return $this;
    }

    /**
     * Get totalIncludingTaxes
     *
     * @return float
     */
    public function getTotalIncludingTaxes()
    {
        return $this->totalIncludingTaxes;
    }
}
