<?php
namespace tests\AppBundle\Controller;

/*if (!class_exists('PHPUnit_Framework_TestCase') && class_exists('PHPUnit\Framework\TestCase')) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}*/

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

Class SecurityControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;
    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLoginRoleUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'user';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('ROLE_USER')
        );
    }
    public function testLoginRoleAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('ROLE_ADMIN')
        );
    }
    public function testBadLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'wrong';
        $form['_password'] = 'wrong';
        $this->client->submit($form);
        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')
        );
    }
}