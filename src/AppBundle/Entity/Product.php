<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 30/05/2018
 * Time: 17:55
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @ORM\Entity()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255, nullable=false)
     * @Assert\NotNull(message="Ajouter un nom à ce produit")
     * @Assert\Length(min="5",max="255", minMessage="Ce champ demande minimum 5 caractères", maxMessage="Ce champ demande maximum 255 caractères")
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotNull(message="Ajouter une quantité à ce produit")
     * @Assert\Type("float")
     * @Assert\Range(min="1", minMessage="Il faut au moins '1' comme quatitée")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("integer")
     * @Assert\Range(max="99", maxMessage="Vous ne pouvez pas faire une réduction de plus de 99%")
     */
    private $percentageDiscount;


    /**
     * @ORM\Column(type="float", scale=2)
     * @Assert\Type("float")
     * @Assert\NotNull(message="Ajouter un prix")
     */
    private $priceOutTaxe;


    /**
     * @ORM\Column(type="float", scale=2,nullable=true)
     */
    private $amountOfTaxe;

    /**
     * @ORM\Column(type="float", scale=2,nullable=true)
     */
    private $priceWithTaxe;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quote", inversedBy="products")
     * @ORM\JoinColumn(name="quote_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $quote;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Invoice", inversedBy="products")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tva", inversedBy="products")
     * @ORM\JoinColumn(name="tva_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Sélectionner une tva.")
     */
    private $tva;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
            $this->setQuote(null);
        }
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set percentageDiscount
     *
     * @param integer $percentageDiscount
     *
     * @return Product
     */
    public function setPercentageDiscount($percentageDiscount)
    {
        $this->percentageDiscount = $percentageDiscount;

        return $this;
    }

    /**
     * Get percentageDiscount
     *
     * @return integer
     */
    public function getPercentageDiscount()
    {
        return $this->percentageDiscount;
    }





    /**
     * Set tva
     *
     * @param \AppBundle\Entity\Tva $tva
     *
     * @return Product
     */
    public function setTva(\AppBundle\Entity\Tva $tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return \AppBundle\Entity\Tva
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set quote
     *
     * @param \AppBundle\Entity\Quote $quote
     *
     * @return Product
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
     * Set invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     *
     * @return Product
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
     * Set priceOutTaxe
     *
     * @param float $priceOutTaxe
     *
     * @return Product
     */
    public function setPriceOutTaxe($priceOutTaxe)
    {
        $this->priceOutTaxe = $priceOutTaxe;

        return $this;
    }

    /**
     * Get priceOutTaxe
     *
     * @return float
     */
    public function getPriceOutTaxe()
    {
        return $this->priceOutTaxe;
    }

    /**
     * Set priceWithTaxe
     *
     * @param float $priceWithTaxe
     *
     * @return Product
     */
    public function setPriceWithTaxe($priceWithTaxe)
    {
        $this->priceWithTaxe = $priceWithTaxe;

        return $this;
    }

    /**
     * Get priceWithTaxe
     *
     * @return float
     */
    public function getPriceWithTaxe()
    {
        return $this->priceWithTaxe;
    }



    /**
     * Set amountOfTaxe
     *
     * @param float $amountOfTaxe
     *
     * @return Product
     */
    public function setAmountOfTaxe($amountOfTaxe)
    {
        $this->amountOfTaxe = $amountOfTaxe;

        return $this;
    }

    /**
     * Get amountOfTaxe
     *
     * @return float
     */
    public function getAmountOfTaxe()
    {
        return $this->amountOfTaxe;
    }
}
