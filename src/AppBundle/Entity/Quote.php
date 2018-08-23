<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 30/05/2018
 * Time: 08:43
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Quote
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuoteRepository")
 */
class Quote
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Le champ 'Numéro de devis' n'est pas rempli")
     * @Assert\Range(min="15000",minMessage="Le 'numéro du devis' doit commencer par 15000 minimum.")
     * @Assert\Type("integer")
     */
    private $numberQuote;


    //We can search this "QUOTE" by "document_name" !
    /**
     * @ORM\Column(type="string",length=100,nullable=true)
     * @Assert\Length(max="100",maxMessage="Le champ 'Nom de dossier' ne doit pas dépasser 100 caratères.")
     * @Assert\Type("string")
     */
    private $documentName;


    //Creation date of quote
    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $creationDate;


    //Billing date of quote
    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $billingDate;

    //Validation deadline of quote
    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $validationDeadline;


    //the customer who requested the quote
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="quotes")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Sélectionner un client.")
     */
    private $customer;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PaymentMethod", inversedBy="quote")
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




    //allows to know if a quote had generated an invoice
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="quote")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $invoice;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="quote",cascade={"persist","remove"})
     * @Assert\NotNull(message="Ajouter un produit")
     * @Assert\Valid(traverse=true)
     */
    private $products;



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


    private $color;
    private $difference;


    /*----------------------------------------------------------------------------------------------------------------*/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->id === null){
            /*date now*/
            $dateNow = new \DateTime("now");
            /*date 1 month later*/
            $date1MonthLater = new \DateTime("now");
            /*date 3 weeks later*/
            $date3Weeks = new \DateTime("now");

            $this->creationDate = $dateNow;
            $this->billingDate = $date1MonthLater ->modify('+1 month');
            $this->validationDeadline = $date3Weeks -> modify('+3 week');
        }
    }

    public function __toString() {
        return $this->numberQuote;
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
     * Set numberQuote
     *
     * @param integer $numberQuote
     *
     * @return Quote
     */
    public function setNumberQuote($numberQuote)
    {
        $this->numberQuote = $numberQuote;

        return $this;
    }

    /**
     * Get numberQuote
     *
     * @return integer
     */
    public function getNumberQuote()
    {
        return $this->numberQuote;
    }

    /**
     * Set documentName
     *
     * @param string $documentName
     *
     * @return Quote
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
     * @return Quote
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
     * @return Quote
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
     * @return Quote
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
     * @return Quote
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
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     *
     * @return Quote
     */
    public function setInvoice(\AppBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \AppBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }



    /**
     * Set validationDeadline
     *
     * @param \DateTime $validationDeadline
     *
     * @return Quote
     */
    public function setValidationDeadline($validationDeadline)
    {
        $this->validationDeadline = $validationDeadline;

        return $this;
    }

    /**
     * Get validationDeadline
     *
     * @return \DateTime
     */
    public function getValidationDeadline()
    {
        return $this->validationDeadline;
    }

    /**
     * Set percentageAdvencePayment
     *
     * @param integer $percentageAdvencePayment
     *
     * @return Quote
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
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Quote
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {

        $product->setQuote($this);
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
     * @return Quote
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
     * @return Quote
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
     * @return Quote
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

    /**
     * @return mixed
     */
    public function getColor()
    {
        $this->setDifference($this->getValidationDeadline()->diff(new \DateTime('now')));
        $difference = $this->getDifference();

        if($difference->d > 15){
            $this->setColor("green");
        }
        elseif ($difference->d <= 15 and $difference->d > 7){
            $this->setColor("orange");
        }elseif($difference->d <= 7){
            $this->setColor("red");
        }

        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getDifference()
    {
        return $this->difference;
    }

    /**
     * @param mixed $difference
     */
    public function setDifference($difference)
    {
        $this->difference = $difference;
    }

}
