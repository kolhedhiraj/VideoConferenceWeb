<?php

namespace VideoConference\LogInBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Tests\Constraints\UrlValidatorTest;

require_once 'PHPUnit/Autoload.php';
class DefaultControllerTest extends WebTestCase {
	public function testIndex() {
		$client = static::createClient ();
		$crawler = $client->request ( 'GET', '/' );
		// Sikeres-e a request
		$this->assertTrue ( $client->getResponse ()->isSuccessful () );
		// Tartalmaz-e ilyen elemeket
		$this->assertTrue ( $crawler->filter ( 'a:contains("Log In")' )->count () > 0 );
		$this->assertTrue ( $crawler->filter ( 'a:contains("Register")' )->count () > 0 );
		// LogIn oldalra vezet-e a link
		$link = $crawler->selectLink ( "Log In" )->link ();
		$crawler = $client->click ( $link );
		$this->assertTrue ( $client->getResponse()->isSuccessful());
		//print($client->getResponse()->headers->__toString());
		//$this->assertTrue($client->getResponse()->isRedirect('/login'));
	}
	public function testLogIn() {
		$client = static::createClient ();
		$crawler = $client->request ( 'GET', '/login' );
		// Sikeres-e a request
		$this->assertTrue ( $client->getResponse ()->isSuccessful () );
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
		//print($client->getResponse()->headers->__toString());
		//$this->assertTrue($client->getResponse()->isRedirect('/'));
	}
}
