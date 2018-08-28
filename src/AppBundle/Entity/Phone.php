<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 30/05/2018
 * Time: 08:11
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Phone
 * @ORM\Entity()
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max="50")
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Type("string")
     * @Assert\Length(max="15", maxMessage="Le numéro de téléphone est trop long.")
     * @Assert\NotNull(message="Donner un numéro de téléphone")
     * @Assert\Regex(pattern="/^(0|\+33)[1-9]([-+,;. ]?[0-9]{2}){4}$/", message="Ceci n'est pas considérer comme un numéro de téléphone")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="phones")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $customer;


    public function __clone() {
        $this->id = null;
        $this->customer = null;
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
     * Set type
     *
     * @param string $type
     *
     * @return Phone
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Phone
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Phone
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
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


}
