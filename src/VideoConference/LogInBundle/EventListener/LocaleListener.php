<?php

namespace VideoConference\LogInBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener implements EventSubscriberInterface {
	private $defaultLocale;
	public function __construct($defaultLocale = "en") {
		$this->defaultLocale = $defaultLocale;
	}
	
	// Feliratkozunk a REQUEST eventre iratkozunk fel az onKernelRequest listenerrel
	public static function getSubscribedEvents() {
		return array (
				KernelEvents::REQUEST => array (
						array (
								'onKernelRequest',
								17 
						) 
				) 
		);
	}
	public function onKernelRequest(GetResponseEvent $event) {
		$request = $event->getRequest ();
		// ha nincs session-ünk, akkor nem csinálunk semmit
		// megnézzük, hogy a request-ben van-e locale változónk
		if ($locale = $request->attributes->get ( '_locale' )) {
			$request->getSession ()->set ( '_locale', $locale );
		} else {
			// ha a session-ben nincs _locale, akkor a default-ot rakjuk bele
			$request->setLocale ( $request->getSession ()->get ( '_locale', $this->defaultLocale ) );
		}
	}
}