<?php

namespace VideoConference\WebsiteBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User entity for the video conference
 * VideoConference\LogInBundle\Entity
 * @author Robert Szabados
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity()
 */
class User extends BaseUser {
	public function __construct(){
		parent::__construct();
		$this->rooms = new ArrayCollection();
		$this->firstName = "";
		$this->lastName="";
	}
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	/**
	 * @ORM\Column(type="string",length=255,name="first_name")
	 */
	protected $firstName;
	/**
	 * @ORM\Column(type="string",length=255,name="last_name")
	 */
	protected $lastName;
	/**
	 * @ORM\OneToMany(targetEntity="Room",mappedBy="owner")
	 */
	protected $rooms;
	/**
	 * @ManyToMany(targetEntity="Room", mappedBy="joinedUsers")
	 **/
	protected $roomsJoined;
	
	/**
	 * Getters and setters
	 */
	public function getFirstName() {
		return $this->firstName;
	}
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		return $this;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		return $this;
	}
	public function getRooms() {
		return $this->rooms;
	}
	public function setRooms($rooms) {
		$this->rooms = $rooms;
		return $this;
	}
	public function getRoomsJoined() {
		return $this->roomsJoined;
	}
	public function setRoomsJoined($roomsJoined) {
		$this->roomsJoined = $roomsJoined;
		return $this;
	}
	
	
}
