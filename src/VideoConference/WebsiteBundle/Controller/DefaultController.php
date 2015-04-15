<?php

namespace VideoConference\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Routing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use VideoConference\WebsiteBundle\Entity\Room;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller {
	
	/**
	 * @Route("/",name="default_index")
	 */
	public function indexAction() {
		// kibányásztuk a repository class-t, ezzel tudunk lekérdezéseket csinálni
		$userRepository = $this->getDoctrine ()->getRepository ( 'VideoConferenceWebsiteBundle:User' );
		$roomRepository = $this->getDoctrine ()->getRepository ( 'VideoConferenceWebsiteBundle:Room' );
		
		return $this->render ( 'VideoConferenceWebsiteBundle:Default:index.html.twig', array (
				'users' => $userRepository->findAll (),
				'rooms' => $roomRepository->findAll () 
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
	
	/**
	 * @Route("/create_room",name="default_create_room")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function createRoomAction(Request $request) {
		$generator = $this->container->get ( 'security.secure_random' );
		$bytes = $generator->nextBytes ( 16 );
		
		$room = new Room ();
		$form = $this->createFormBuilder ( $room )->add ( 'name' )->add ( 'description' )->add ( 'maxUsers' )->add ( 'isPublic', 'checkbox', array (
				'required' => false 
		) )->getForm ();
		// ////////////////////////////////////////////////////////////////////////////
		// ////////////////////////////////////////////////////////////////////////////
		
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$room->setToken ( bin2hex ( $bytes ) );
			
			$room->setOwner ( $this->getUser () );
			$room->setJoinedUsers ( new ArrayCollection () );
			$em->persist ( $room );
			$em->flush ();
			return $this->redirectToRoute ( 'default_manage_rooms' );
		}
		
		return $this->render ( "VideoConferenceWebsiteBundle:Default:createRoom.html.twig", array (
				'form' => $form->createView () 
		) );
	}
	
	/**
	 * @Route("/manage_rooms",name="default_manage_rooms")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function manageRoomsAction(Request $request) {
		$currentUser=$this->container->get ( 'security.context' )->getToken ()->getUser ();
		if ($this->getUser ()->getRooms ()->count () != null) {
			 
			
			$users = $this->getDoctrine ()->getRepository ( 'VideoConferenceWebsiteBundle:User' )->findAll ();
			$publicRooms = new ArrayCollection ();
			
			foreach ( $users as $user ) {
				$rooms = $user->getRooms ();
				foreach ( $rooms as $room ) {
					if ($room->getIsPublic ()&&($room->getOwner()!==$currentUser)) {
						$publicRooms->add ( $room );
					}
				}
			}
			
			return $this->render ( "VideoConferenceWebsiteBundle:Default:manageRooms.html.twig", array (
					'rooms' => $this->getUser ()->getRooms (),
					'publicRooms' => $publicRooms, 
			) );
		} else {
			return $this->render ( "VideoConferenceWebsiteBundle:Default:manageRooms.html.twig", array (
					'rooms' => null 
			) );
		}
	}
	
	/**
	 * @Route("/delete_room/{id}",name="default_delete_room")
	 * @ParamConverter("room", class="VideoConferenceWebsiteBundle:Room")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function deleteRoomAction(Request $request, Room $room) {
		$formData = array (
				'room_id' => $room->getId () 
		);
		
		$deleteForm = $this->createFormBuilder ( $formData )->setAction ( $this->generateUrl ( 'default_delete_room', array (
				'id' => $room->getId () 
		) ) )->setMethod ( 'DELETE' )->add ( 'room_id', 'hidden' )->add ( 'delete', 'submit', array (
				'attr' => array (
						'class' => 'button' 
				) 
		) )->add ( 'cancel', 'submit', array (
				'attr' => array (
						'class' => 'button' 
				) 
		) )->getForm ();
		
		$deleteForm->handleRequest ( $request );
		
		// Ha valid a form és a szoba az adott userhez tartozik, akkor törli
		if ($deleteForm->isValid () && $room->getOwner () == $this->get ( 'security.context' )->getToken ()->getUser ()) {
			if ($deleteForm->get ( 'delete' )->isClicked ()) {
				$em = $this->getDoctrine ()->getManager ();
				$em->remove ( $room );
				$em->flush ();
			}
			
			return $this->redirectToRoute ( 'default_manage_rooms' );
		}
		
		return $this->render ( "VideoConferenceWebsiteBundle:Default:deleteRoom.html.twig", array (
				'delete_form' => $deleteForm->createView () 
		) );
	}
	
	/**
	 * @Route("/modify_room/{id}",name="default_modify_room")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function modifyRoomAction(Request $request, $id) {
		$room = $this->getDoctrine ()->getRepository ( 'VideoConferenceWebsiteBundle:Room' )->find ( $id );
		
		$form = $this->createFormBuilder ()->add ( 'name', null, array (
				'required' => false,
				'data' => $room->getName () 
		) )->add ( 'description', null, array (
				'required' => false,
				'data' => $room->getDescription () 
		) )->add ( 'maxUsers', 'integer', array (
				'required' => false,
				'data' => $room->getMaxUsers () 
		) )->add ( 'isPublic', 'checkbox', array (
				'label' => 'Public',
				'required' => false,
				'data' => $room->getIsPublic (),
				'attr' => array (
						'class' => 'input[type="checkbox"]' 
				) 
		) )->getForm ();
		
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$room->setName ( $form->get ( 'name' )->getData () );
			$room->setDescription ( $form->get ( 'description' )->getData () );
			$room->setMaxUsers ( $form->get ( 'maxUsers' )->getData () );
			$room->setIsPublic ( $form->get ( 'isPublic' )->getData () );
			$em->persist ( $room );
			$em->flush ();
			return $this->redirect ( $this->generateUrl ( 'default_manage_rooms' ) );
		}
		return $this->render ( "VideoConferenceWebsiteBundle:Default:modifyRoom.html.twig", array (
				'form' => $form->createView () 
		) );
	}
	
	/**
	 * @Route("/modify_user",name="default_modify_user")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function modifyUserAction(Request $request) {
		$userManager = $this->container->get ( 'fos_user.user_manager' );
		$user = $this->container->get ( 'security.context' )->getToken ()->getUser ();
		
		$form = $this->createFormBuilder ()->add ( 'username', null, array (
				'required' => true,
				'data' => $user->getUsername () 
		) )->add ( 'first_name', null, array (
				'required' => true,
				'data' => $user->getFirstName () 
		) )->add ( 'last_name', null, array (
				'required' => true,
				'data' => $user->getLastName () 
		) )->add ( 'email', null, array (
				'required' => true,
				'data' => $user->getEmail () 
		) )->add ( 'new_password', 'password', array (
				'required' => false,
				'data' => $user->getPlainPassword () 
		) )->add ( 'verify_password', 'password', array (
				'required' => false 
		) )->getForm ();
		
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			
			$user->setUsername ( $form->get ( 'username' )->getData () );
			$user->setFirstName ( $form->get ( 'first_name' )->getData () );
			$user->setLastName ( $form->get ( 'last_name' )->getData () );
			$user->setEmail ( $form->get ( 'email' )->getData () );
			
			if ($form->get ( 'new_password' )->getData () !== null && $form->get ( 'new_password' )->getData () === $form->get ( 'verify_password' )->getData ()) {
				$user->setPlainPassword ( $form->get ( 'new_password' )->getData () );
			} elseif ($form->get ( 'new_password' )->getData () !== null && $form->get ( 'new_password' )->getData () !== $form->get ( 'verify_password' )->getData ()) {
				return $this->render ( "VideoConferenceWebsiteBundle:Default:modifyUser.html.twig", array (
						'form' => $form->createView (),
						'message' => 'Passwords does not match' 
				) );
			}
			
			$userManager->updateUser ( $user );
			
			return $this->render ( "VideoConferenceWebsiteBundle:Default:modifyUser.html.twig", array (
					'form' => $form->createView (),
					'message' => 'Profile modified' 
			) );
		}
		
		return $this->render ( "VideoConferenceWebsiteBundle:Default:modifyUser.html.twig", array (
				'form' => $form->createView (),
				'message' => '' 
		) );
	}
	
	/**
	 * @Route("/join_room/{id}",name="default_join_room")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function videoStreamAction($id) {
		$user = $this->container->get ( 'security.context' )->getToken ()->getUser ();
		$room = $this->getDoctrine ()->getRepository ( 'VideoConferenceWebsiteBundle:Room' )->find ( $id );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$joinedUsers = new ArrayCollection ();
		$joinedUsers = $room->getJoinedUsers ();
		
		if(!$joinedUsers->contains($user)){		
		$joinedUsers->add ( $user );
		}
		
		$room->setJoinedUsers ( $joinedUsers );
		$em->persist ( $room );
		$em->flush ();
		
		return $this->render ( "VideoConferenceWebsiteBundle:Default:videoStream.html.twig", array (
				'user' => $user,
				'room' => $room 
		) );
	}
	

	
	
	/**
	 * @Route("/delete_user",name="default_delete_user")
	 * @Security("has_role('ROLE_USER')")
	 */
	public function deleteUserAction(Request $request) {
		
		$user = $this->container->get ( 'security.context' )->getToken ()->getUser ();
		
		$formData = array (
				'user_id' => $user->getId ()
		);
	
		$deleteForm = $this->createFormBuilder ( $formData )->setAction ( $this->generateUrl ( 'default_delete_user', array (
				'id' => $user->getId ()
		) ) )->setMethod ( 'DELETE' )->add ( 'user_id', 'hidden' )->add ( 'delete', 'submit', array (
				'attr' => array (
						'class' => 'button'
				)
		) )->add ( 'cancel', 'submit', array (
				'attr' => array (
						'class' => 'button'
				)
		) )->getForm ();
	
		$deleteForm->handleRequest ( $request );
	
		// Ha valid a form és a szoba az adott userhez tartozik, akkor törli
		if ($deleteForm->isValid ()&&$user!=null) {
			if ($deleteForm->get ( 'delete' )->isClicked ()) {
				$em = $this->getDoctrine ()->getManager ();
				foreach ( $user->getRooms() as $room ) {
					$em->remove($room);
				}
				$em->remove ( $user );
				$em->flush ();
			}
			$this->container->get('session')->invalidate(1);
			return $this->redirectToRoute ( 'fos_user_security_logout' );
		}
	
		return $this->render ( "VideoConferenceWebsiteBundle:Default:deleteUser.html.twig", array (
				'delete_form' => $deleteForm->createView ()
		) );
	}
}
