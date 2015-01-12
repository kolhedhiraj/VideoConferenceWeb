<?php
namespace VideoConference\LogInBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VideoConference\LogInBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$userAdmin = new User();
		$userAdmin->setUsername('admin');
		$userAdmin->setPassword('test');
		$userAdmin->setEmail('test@test.com');
		$userAdmin->setFirstName('elek');
		$userAdmin->setLastName('test');

		$manager->persist($userAdmin);
		$manager->flush();
	}
}