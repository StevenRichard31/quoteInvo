<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 24/08/2018
 * Time: 15:49
 */

namespace AppBundle\Event;


use AppBundle\Entity\Quote;
use Symfony\Component\EventDispatcher\Event;

class QuoteEvent extends Event
{

    private $quote;

    /**
     * QuoteEvent constructor.
     * @param $quote
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * @return mixed
     */
    public function getQuote()
    {
        return $this->quote;
    }

}