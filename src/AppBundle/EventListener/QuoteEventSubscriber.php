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

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            AppBundleEvents::ADD_QUOTE => [
                // les numero 10,-10... sont des ordres de priorité le plus élever est actionner en premier
                ["addUserLog",10],
                ["sendMail",-10]
            ],
            AppBundleEvents::SEND_MAIL => "sendMail"
        ];
    }

    public function addUserLog(QuoteEvent $event){

       // dump('1');
    }

    public function sendMail(QuoteEvent $event){
        //dump('2'); die();

    }
}