<?php
namespace VideoConference\LogInBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="acme_role")
 * @ORM\Entity()
 */
class Role implements RoleInterface
{
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="name", type="string", length=30)
	 */
	private $name;

	/**
	 * @ORM\Column(name="role", type="string", length=20, unique=true)
	 */
	private $role;

	/**
	 * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
	 */
	private $users;

	public function __construct()
	{
		$this->users = new ArrayCollection();
	}

	/**
	 * @see RoleInterface
	 */
	public function getRole()
	{
		return $this->role;
	}
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}
	public function getUsers() {
		return $this->users;
	}
	public function setUsers($users) {
		$this->users = $users;
		return $this;
	}
	

	
}