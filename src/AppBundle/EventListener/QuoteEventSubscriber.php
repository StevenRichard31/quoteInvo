<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 24/08/2018
 * Time: 14:36
 */

namespace AppBundle\EventListener;


use AppBundle\AppBundleEvents;
use AppBundle\Event\QuoteEvent;
use AppBundle\Manager\GeneratorNumberQuoteManager;
use AppBundle\Manager\QuoteManager;
use Components\Utils\CountFunction;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_Environment;


class QuoteEventSubscriber implements EventSubscriberInterface
{

    private $utilsCountFunction;
    private $twig;


    /**
     * QuoteSubscriber constructor.
     * @param CountFunction $utilsCountFunction
     * @param Twig_Environment $twig
     */
    public function __construct(CountFunction $utilsCountFunction, Twig_Environment $twig )
    {
        $this->utilsCountFunction = $utilsCountFunction;
        $this->twig = $twig;

    }


    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            AppBundleEvents::ADD_QUOTE => [
                ["addUserLog",10],
                ["sendMail",-10]
            ],
            AppBundleEvents::SEND_MAIL => "sendMail"
        ];
    }

    public function addUserLog(QuoteEvent $event){


    }

    public function sendMail(QuoteEvent $event){


    }
}