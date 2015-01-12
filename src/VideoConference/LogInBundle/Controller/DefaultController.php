<?php

namespace VideoConference\LogInBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Routing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller {
	/**
	 * @Route("/",name="default_index")
	 */
	public function indexAction() {
		// kibányásztuk a repository class-t, ezzel tudunk lekérdezéseket csinálni
		$repository = $this->getDoctrine ()->getRepository ( 'VideoConferenceLogInBundle:User' );
		
		return $this->render ( 'VideoConferenceLogInBundle:Default:index.html.twig', array (
				'users' => $repository->findAll () 
		) );
	}
	// Itt a _locale-t kapjuk meg paraméternek majd, pl hu vagy en
	/**
	 * @Route("/{_locale}",name="default_locale",requirements={
	 * "_locale": "en|hu"})
	 */
	public function localeAction() {
		return $this->redirect ( $this->generateUrl ( 'default_index' ) );
	}
}
