<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 30/05/2018
 * Time: 17:32
 */

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentMethod
 * @ORM\Entity()
 */
class PaymentMethod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\NotNull(message=" Le champ (Nom) ne peut être vide.")
     * @Assert\Length( min="5", max="100", minMessage="Le nom  doit faire au moins 5 caractères", maxMessage="Le nom ne doit pas faire plus de 100 caractères"  )
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quote", mappedBy="paymentMethod")
     */
    private $quote;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invoice", mappedBy="paymentMethod")
     */
    private $invoices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quote = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PaymentMethod
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
     * Add quote
     *
     * @param \AppBundle\Entity\Quote $quote
     *
     * @return PaymentMethod
     */
    public function addQuote(\AppBundle\Entity\Quote $quote)
    {
        $this->quote[] = $quote;

        return $this;
    }

    /**
     * Remove quote
     *
     * @param \AppBundle\Entity\Quote $quote
     */
    public function removeQuote(\AppBundle\Entity\Quote $quote)
    {
        $this->quote->removeElement($quote);
    }

    /**
     * Get quote
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Add invoice
     *
     * @param \AppBundle\Entity\Invoice $invoice
     *
     * @return PaymentMethod
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
}
