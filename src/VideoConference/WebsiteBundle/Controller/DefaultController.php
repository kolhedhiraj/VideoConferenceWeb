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

class DefaultController extends Controller {

    /**
     * @Route("/",name="default_index")
     */
    public function indexAction() {
        // kibányásztuk a repository class-t, ezzel tudunk lekérdezéseket csinálni
        $userRepository = $this->getDoctrine()->getRepository('VideoConferenceWebsiteBundle:User');
        $roomRepository = $this->getDoctrine()->getRepository('VideoConferenceWebsiteBundle:Room');

        return $this->render('VideoConferenceWebsiteBundle:Default:index.html.twig', array(
                    'users' => $userRepository->findAll(),
                    'rooms' => $roomRepository->findAll()
        ));
    }

    // Itt a _locale-t kapjuk meg paraméternek majd, pl hu vagy en
    /**
     * @Route("/{_locale}",name="default_locale",requirements={
     * "_locale": "en|hu"})
     */
    public function localeAction() {
        return $this->redirect($this->generateUrl('default_index'));
    }

    /**
     * @Route("/create_room",name="default_create_room")
     * @Security("has_role('ROLE_USER')")
     */
    public function createRoomAction(Request $request) {
        $room = new Room ();
        $form = $this->createFormBuilder($room)->add('name')->add('description')->add('maxUsers')->add('isPublic', null, array(
                    'required' => false
                ))->add('save', 'submit', array(
                    'label' => 'Create room'
                ))->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $room->setToken('tralala');
            $room->setOwner($this->getUser());
            $em->persist($room);
            $em->flush();
            return $this->redirectToRoute('default_manage_rooms');
        }

        return $this->render("VideoConferenceWebsiteBundle:Default:createRoom.html.twig", array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/manage_rooms",name="default_manage_rooms")
     * @Security("has_role('ROLE_USER')")
     */
    public function manageRoomsAction(Request $request) {
        if ($this->getUser()->getRooms()->count() != null) {
            return $this->render("VideoConferenceWebsiteBundle:Default:manageRooms.html.twig", array(
                        'rooms' => $this->getUser()->getRooms()
            ));
        } else {
            return $this->render("VideoConferenceWebsiteBundle:Default:manageRooms.html.twig", array(
                        'rooms' => null
            ));
        }
    }

    /**
     * @Route("/delete_room/{id}",name="default_delete_room")
     * @ParamConverter("room", class="VideoConferenceWebsiteBundle:Room")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteRoomAction(Request $request, Room $room) {
        $formData = array(
            'room_id' => $room->getId(),
        );

        $deleteForm = $this->createFormBuilder($formData)
                ->setAction($this->generateUrl('default_delete_room', array('id' => $room->getId())))
                ->setMethod('DELETE')
                ->add('room_id', 'hidden')
                ->add('delete', 'submit')
                ->add('cancel', 'submit')
                ->getForm();

        $deleteForm->handleRequest($request);

        if ($deleteForm->isValid()) {
            if ($deleteForm->get('delete')->isClicked()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($room);
                $em->flush();
            }

            return $this->redirectToRoute('default_manage_rooms');
        }

        return $this->render("VideoConferenceWebsiteBundle:Default:deleteRoom.html.twig", array(
                    'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * @Route("/modify_room/{id}",name="default_modify_room")
     * @Security("has_role('ROLE_USER')")
     */
    public function modifyRoomAction(Request $request, $id) {
        $room = $this->getDoctrine()->getRepository('VideoConferenceWebsiteBundle:Room')->find($id);
        $form = $this->createFormBuilder()->add('name', null, array('required' => false, 'data' => $room->getName()))
                        ->add('description', null, array('required' => false, 'data' => $room->getDescription()))
                        ->add('maxUsers', 'integer', array('required' => false, 'data' => $room->getMaxUsers()))
                        ->add('isPublic', 'checkbox', array(
                            'label' => 'Public',
                            'required' => false,
                            'data' => $room->getIsPublic()
                        ))->add('save', 'submit', array(
                    'label' => 'Modify room'
                ))->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $room->setName($form->get('name')->getData());
            $room->setDescription($form->get('description')->getData());
            $room->setMaxUsers($form->get('maxUsers')->getData());
            $room->setIsPublic($form->get('isPublic')->getData());
            $em->persist($room);
            $em->flush();
            return $this->redirect($this->generateUrl('default_manage_rooms'));
        }

        return $this->render("VideoConferenceWebsiteBundle:Default:modifyRoom.html.twig", array(
                    'form' => $form->createView()
        ));
    }

}
