<?php

namespace VideoConference\LogInBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * VideoConference\LogInBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="VideoConference\LogInBundle\Entity\User")
 */
class User implements AdvancedUserInterface, \Serializable {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	private $username;
	
	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;
	
	/**
	 * @ORM\Column(type="string", length=60, unique=true)
	 */
	private $email;
	
	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
	 */
	private $roles;
	public function __construct() {
		$this->roles = new ArrayCollection ();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getSalt() {
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getRoles() {
		return $this->roles->toArray ();
	}
	
	/**
	 * @inheritDoc
	 */
	public function eraseCredentials() {
	}
	
	/**
	 *
	 * @see \Serializable::serialize()
	 */
	public function serialize() {
		return serialize ( array (
				$this->id,
				$this->username,
				$this->password 
		)
		// see section on salt below
		// $this->salt,
		 );
	}
	
	/**
	 *
	 * @see \Serializable::unserialize()
	 */
	public function unserialize($serialized) {
		list ( $this->id, $this->username, $this->password, )
		// see section on salt below
		// $this->salt
		 = unserialize ( $serialized );
	}
	public function isAccountNonExpired() {
		return true;
	}
	public function isAccountNonLocked() {
		return true;
	}
	public function isCredentialsNonExpired() {
		return true;
	}
	public function isEnabled() {
		return $this->isActive;
	}
}