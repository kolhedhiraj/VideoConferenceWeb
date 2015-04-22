<?php

namespace VideoConference\WebsiteBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener implements EventSubscriberInterface {

    private $defaultLocale;
/**
 * Sets the defaultLocale variable to "en" (english)
 * @param string $defaultLocale
 */
    public function __construct($defaultLocale = "en") {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Subscribes to the REQUEST event with the onKernelRequest listener
     */
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::REQUEST => array(
                array(
                    'onKernelRequest',
                    17
                )
            )
        );
    }

    /**
     * The onKernelRequest listener.
     * Gets the request's locale and puts it in the session
     * @param GetResponseEvent $event allows to create a response for a request
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

}
