<?php

namespace VideoConference\WebsiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Tests\Constraints\UrlValidatorTest;


require_once 'PHPUnit/Autoload.php';

/**
 * Test class for the default controller class
 * 
 * @author Robert Szabados
 *
 */
class DefaultControllerTest extends WebTestCase {
	
	/**
	 * Tests for the index action
	 */
	public function testIndex() {
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler = $client->request ( 'GET', '/' );
		
		// Sikeres-e a request
		$this->assertTrue ( $client->getResponse ()->isSuccessful () );
		
		// Tartalmaz-e ilyen elemeket
		$this->assertCount(5, $crawler->filter('a'));
		$this->assertTrue ( $crawler->filter ( 'a:contains("Log In")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("Register")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("HU")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("EN")' )->count () > 0 );
		
		
		// Működik-e a link
		$link = $crawler->selectLink ( "Log In" )->link ();
		$crawler = $client->click ( $link );
		$crawler = $client->getResponse ();
		$this->assertEquals ( 200, $crawler->getStatusCode () );
		
		
	}
	
	/**
	 * Tests for the login action
	 */
	public function testLogIn() {
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler = $client->request ( 'GET', '/login' );
		
		// Sikeres-e a request
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		
		// Tartalmaz-e ilyen elemeket
		$this->assertTrue ( $crawler->filter ( 'a:contains("Log In")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("Register")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Username")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Password")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Remember me")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Login")' )->count () > 0 );
		
		// Form teszt
		$buttonCrawlerNode = $crawler->selectButton ( '_submit' );
		$form = $buttonCrawlerNode->form ();
		$form ['_username'] = 'admin';
		$form ['_password'] = 'test';
		$client->submit ( $form );
		$crawler = $client->getResponse ();
		// print($crawler);
		$this->assertEquals ( 200, $crawler->getStatusCode () );
	}
	
	/**
	 * Tests for the register action
	 */
	public function testRegister() {
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler = $client->request ( 'GET', '/register' );
		
		// Sikeres-e a request
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		
		// Tartalmaz-e ilyen elemeket
		$this->assertTrue ( $crawler->filter ( 'html:contains("Username")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Password")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Email")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Verification")' )->count () > 0 );
		
		// Form teszt
		$buttonCrawlerNode = $crawler->selectButton ( 'Register' );
		$form = $buttonCrawlerNode->form ();
		$form ['fos_user_registration_form[username]'] = 'test_user';
		$form ['fos_user_registration_form[email]'] = 'test_email@test.com';
		$form ['fos_user_registration_form[plainPassword][first]'] = 'test_password';
		$form ['fos_user_registration_form[plainPassword][second]'] = 'test_password';
		// print(implode(" ",$form->getValues()));
		$client->submit ( $form );
		$crawler = $client->getResponse ();
		// print($crawler);
		$this->assertEquals ( 200, $crawler->getStatusCode () );
	}
	
	/**
	 * Tests for the profile modification action
	 */
	public function testModifyUser() {
		
		//Bejelentkezés
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler=$client->request('GET','/login');
		$buttonCrawlerNode = $crawler->selectButton ( '_submit' );
		$form = $buttonCrawlerNode->form ();
		$form ['_username'] = 'admin';
		$form ['_password'] = 'test';
		$client->submit ( $form );
		
		//Edit profile
		$crawler = $client->request ( 'GET', '/modify_user' );
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Log Out")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Username")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("First name")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Last name")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Email")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("New password")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Verify password")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Update profile")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Delete profile")' )->count () > 0 );
	
	}
	
	/**
	 * Tests for the createRoom action
	 */
	public function testCreateRoom() {
		
		//Log In
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler=$client->request('GET','/login');
		$buttonCrawlerNode = $crawler->selectButton ( '_submit' );
		$form = $buttonCrawlerNode->form ();
		$form ['_username'] = 'admin';
		$form ['_password'] = 'test';
		$client->submit ( $form );
		
		//Create room
		$crawler = $client->request ( 'GET', '/create_room' );
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Log Out")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Name")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Description")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Max users")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Public?")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Create room")' )->count () > 0 );
		
	}
	/**
	 * Tests for the room management action
	 */
	public function testManageRooms() {
	
		//Log In
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler=$client->request('GET','/login');
		$buttonCrawlerNode = $crawler->selectButton ( '_submit' );
		$form = $buttonCrawlerNode->form ();
		$form ['_username'] = 'admin';
		$form ['_password'] = 'test';
		$client->submit ( $form );
	
		//Create room
		$crawler = $client->request ( 'GET', '/manage_rooms' );
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Log Out")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Name")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Description")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Max users")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Created at")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Public")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Owner")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Joined users")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Join")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Modify")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Delete")' )->count () > 0 );
		
		
	}
}
