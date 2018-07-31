<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 21/06/2018
 * Time: 17:32
 */

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


class Search
{


    /**
     * @Assert\NotNull()
     * @Assert\Type("string")
     */
    private $keyword;



    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Search
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
}
