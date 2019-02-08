<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 07/09/2018
 * Time: 15:08
 */

namespace AppBundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig_Environment;

class ExceptionEventSubscriber implements EventSubscriberInterface
{

    private $twig;

    /**
     * ExceptionEventSubscriber constructor.
     * @param $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => "handleError"
        ];
    }


    //permet de rediriger vers une page l'exception "catcher"
    /**
     * @param GetResponseForExceptionEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handleError(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $content = $this->twig->render("@App/error/index.html.twig", ["exception" => $exception]);

        $code = 500;
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
        }

        $event->setResponse(new Response($content, $code));
    }
}