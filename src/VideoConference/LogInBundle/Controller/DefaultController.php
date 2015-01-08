<?php

namespace VideoConference\LogInBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Routing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class DefaultController extends Controller {
/**
 *@Route("/")
 */
	public function indexAction() {
		return $this->render ( 'VideoConferenceLogInBundle:Default:index.html.twig' );
	}
	
/**
 *@Route("/admin")
 */
	public function adminAction() {
		return $this->render('VideoConferenceLogInBundle:Default:login.html.twig');
	}
}
