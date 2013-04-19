<?php

namespace JJs\Bundle\ResponseConverterBundle\Response;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use JJs\Bundle\ResponseConverterBundle\Converter\ConverterInterface;

/**
 * Response Converter Subscriber
 *
 * Subscribes to kernel events and enacts compatible response conversions.
 *
 * @author Josiah <josiah@web-dev.com.au>
 */
class ConverterSubscriber implements EventSubscriberInterface
{
    /**
     * Response converter
     * 
     * @var ConverterInterface
     */
    protected $converter;

    /**
     * Instantiates the response converter subscriber
     * 
     * @param ConverterInterface $converter
     *        Converter with which to convert responses
     */
    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
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
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => ['onResponse', 255],
        ];
    }

    /**
     * Returns the response converter
     * 
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * Checks the request for a pdf response format converts the html to a pdf
     * document when required.
     * 
     * @param FilterResponseEvent $event
     *        Event which should be handled
     */
    public function onResponse(FilterResponseEvent $event)
    {
        // Only handle the response for master requests. If we filter a
        // sub-request than we're going to produce a garbage document when its
        // pulled together by the master requests.
        // 
        // TODO: Handle ESI configurations
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) return;

        $converter = $this->getConverter();
        $request   = $event->getRequest();
        $response  = $event->getResponse();

        // Ensure the response is convertable
        if (!$converter->isConvertable($request, $response)) return;

        // Set the converted response
        $event->setResponse($converter->convert($request, $response));
    }
}