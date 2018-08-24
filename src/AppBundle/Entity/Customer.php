<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 29/05/2018
 * Time: 20:16
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * Class Customer
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    const STATUS_NEW ='new';
    const STATUS_OLD ='old';
    private $status;



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotNull(message=" Le champ (Nom) ne peut être vide. ")
     * @Assert\Length( min="5", max="255", minMessage="Le nom du client doit faire au moins 5 caractères", maxMessage="Le nom du client ne doit pas faire plus de 255 caractères"  )
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\Email(checkMX = true)
     */
    private $mail;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Address", inversedBy="customer", cascade={"remove","persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Phone", mappedBy="customer",cascade={"remove","persist"})
     * @Assert\NotNull(message="Ajouter un numéro de téléphone")
     * @Assert\Valid(traverse=true)
     * @CustomAssert\CheckPhone()
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quote", mappedBy="customer")
     */
    private $quotes;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invoice", mappedBy="customer")
     */
    private $invoices;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->quotes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
        }
    }

    public function __toString() {
        return $this->name;
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
     * @return Customer
     */
    public function setName($name)
    {

        $name = trim($name);
        $name = str_replace(" ","",$name);
        $name = strtolower($name);

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
     * Set mail
     *
     * @param string $mail
     *
     * @return Customer
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return Customer
     */
    public function setAddress(\AppBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add phone
     *
     * @param \AppBundle\Entity\Phone $phone
     *
     * @return Customer
     */
    public function addPhone(\AppBundle\Entity\Phone $phone)
    {


        $phone->setCustomer($this);
        $this->phones->add($phone);

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \AppBundle\Entity\Phone $phone
     */
    public function removePhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add quote
     *
     * @param \AppBundle\Entity\Quote $quote
     *
     * @return Customer
     */
    public function addQuote(\AppBundle\Entity\Quote $quote)
    {
        $this->quotes[] = $quote;

        return $this;
    }

    /**
     * Remove quote
     *
     * @param \AppBundle\Entity\Quote $quote
     */
    public function removeQuote(\AppBundle\Entity\Quote $quote)
    {
        $this->quotes->removeElement($quote);
    }

    /**
     * Get quotes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuotes()
    {
        return $this->quotes;
    }

    /**
     * Add invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     *
     * @return Customer
     */
    public function addInvoice(\AppBundle\Entity\Invoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\AppBundle\Entity\Invoice $invoice)
    {
        $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
