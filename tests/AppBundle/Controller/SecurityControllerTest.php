<?php
namespace tests\AppBundle\Controller;


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

    public function testLoginShow()
    {
        $client = static::createClient();$client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testAdminLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "admin";
        $form['_password'] = "admin";
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('a:contains("Se déconnecter")')->count());
    }
    public function testUserLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "user";
        $form['_password'] = "user";
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('a:contains("Se déconnecter")')->count());
    }
    public function testWrongLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "user";
        $form['_password'] = "wrong_password";
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
}