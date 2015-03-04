<?php
namespace VideoConference\WebsiteBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VideoConference\WebsiteBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$userAdmin = new User();
		$userAdmin->setUsername('admin');
		$userAdmin->setPlainPassword('test');
		$userAdmin->setEnabled(true);
		$userAdmin->setRoles(array('ROLE_ADMIN'));
		$userAdmin->setEmail('test@test.com');
		$userAdmin->setFirstName('elek');
		$userAdmin->setLastName('test');

		$manager->persist($userAdmin);
		$manager->flush();
	}
}