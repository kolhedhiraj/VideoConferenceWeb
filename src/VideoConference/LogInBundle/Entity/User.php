<?php

namespace VideoConference\LogInBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * VideoConference\LogInBundle\Entity
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity()
 */
class User extends BaseUser {
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
}
