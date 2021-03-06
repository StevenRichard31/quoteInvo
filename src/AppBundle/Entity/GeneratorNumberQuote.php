<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 10/07/2018
 * Time: 08:17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GeneratorNumberQuoteRepository")
 */
class GeneratorNumberQuote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=false)
     */
    private $number;

    /**
     * GeneratorNumberQuote constructor.
     * @param $number
     */
    public function __construct()
    {
        $this->number = 0;
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
     * Set number
     *
     * @param integer $number
     *
     * @return GeneratorNumberQuote
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
}
