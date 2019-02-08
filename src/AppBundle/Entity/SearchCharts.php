<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 28/08/2018
 * Time: 16:36
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class SearchCharts
{
    /**
    * @Assert\DateTime()
    */
    private $date;

    /**
     * SearchCharts constructor.
     * @param string $year
     */
    public function __construct()
    {
        $this->date = new\DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



}