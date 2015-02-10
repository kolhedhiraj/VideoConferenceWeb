<?php

namespace VideoConference\LogInBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VideoConference\LogInBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Szoba a videokonferenciához
 *
 * @ORM\Table(name="room")
 * @ORM\Entity()
 */
class Room{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	/**
	 * @ORM\Column(type="string",length=255,name="token")
	 */
	protected $token;
	/**
	 * @ORM\Column(type="string",length=255,name="name")
	 */
	protected $name;
	/**
	 * @ORM\Column(type="string",length=255,name="description")
	 */
	protected $description;
	/**
	 * Hivatkozunk a felhasználóra (Users)
	 * @ORM\JoinColumn(name="owner_id",nullable=false,referencedColumnName="id")
	 * @ORM\ManyToOne(targetEntity="User") 
	 */
	protected $owner;
	
	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",name="createdat")
	 */
	protected $createdAt;
	
	/**
	 * @ORM\Column(type="integer",name="max_users")
	 */
	protected $maxUsers;
	/**
	 * @ORM\Column(type="boolean",name="is_public")
	 */
	protected $isPublic;
	
	public function getId() {
		return $this->id;
	}
	public function getToken() {
		return $this->token;
	}
	public function setToken($token) {
		$this->token = $token;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function getOwner() {
		return $this->owner;
	}
	public function setOwner(User $owner) {
		$this->owner = $owner;
		return $this;
	}
	public function getCreatedAt() {
		return $this->createdAt;
	}
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
		return $this;
	}
	public function getMaxUsers() {
		return $this->maxUsers;
	}
	public function setMaxUsers($maxUsers) {
		$this->maxUsers = $maxUsers;
		return $this;
	}
	public function getIsPublic() {
		return $this->isPublic;
	}
	public function setIsPublic($isPublic) {
		$this->isPublic = $isPublic;
		return $this;
	}
	
		
}
