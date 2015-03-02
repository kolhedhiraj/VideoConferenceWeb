<?php

namespace VideoConference\LogInBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Tests\Constraints\UrlValidatorTest;

require_once 'PHPUnit/Autoload.php';
class DefaultControllerTest extends WebTestCase {
	public function testIndex() {
		$client = static::createClient ();
		$client->followRedirects ( true );
		$crawler = $client->request ( 'GET', '/' );
		
		// Sikeres-e a request
		$this->assertTrue ( $client->getResponse ()->isSuccessful () );
		
		// Tartalmaz-e ilyen elemeket
		$this->assertTrue ( $crawler->filter ( 'a:contains("Log In")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("Register")' )->count () > 0 );
		
		// LogIn oldalra vezet-e a link
		$link = $crawler->selectLink ( "Log In" )->link ();
		$crawler = $client->click ( $link );
		$crawler = $client->getResponse ();
		$this->assertEquals ( 200, $crawler->getStatusCode () );
	}
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
	public function testManageProfile() {
		
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
		$crawler = $client->request ( 'GET', '/profile/edit' );
		$this->assertEquals ( 200, $client->getResponse ()->getStatusCode () );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Username")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Email")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Current password")' )->count () > 0 );
		// ********************
		// ***IDE MÉG KÓDOT****
		// ********************
	}
	public function testCreateRoom() {
		
		//Bejelentkezés
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
		$this->assertTrue ( $crawler->filter ( 'html:contains("Name")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Description")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'html:contains("Max users")' )->count () > 0 );
		// ********************
		// ***IDE MÉG KÓDOT****
		// ********************
	}
}
