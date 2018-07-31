<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 29/05/2018
 * Time: 16:22
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class address
 * @ORM\Entity()
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string" , length=255, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    private $town;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length( min="5", max="5", maxMessage="Le code postal doit faire  {{ limit }} caractères.", minMessage="Le code postal doit faire  {{ limit }} caractères.")
     * @Assert\Type("integer")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     * @Assert\Length(max="50")
     * @Assert\Type("string")
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    private $building;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Customer", mappedBy="address")
     */
    private $customer;



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
     * Set street
     *
     * @param string $street
     *
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return Address
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set postalCode
     *
     * @param integer $postalCode
     *
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return integer
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set building
     *
     * @param string $building
     *
     * @return Address
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Address
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
