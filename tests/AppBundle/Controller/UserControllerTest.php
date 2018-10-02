<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 22:40
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;
    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function logIn($role = 'ROLE_USER')
    {
        if ($role == 'ROLE_USER') {
            $crawler =  $this->client->request('GET', '/login');
            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user';
            $form['_password'] = 'user';
            $this->client->submit($form);
            return;
        }
        if ($role == 'ROLE_ADMIN') {
            $crawler =  $this->client->request('GET', '/login');
            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'admin';
            $form['_password'] = 'admin';
            $this->client->submit($form);
        }
    }

    public function testListUser()
    {
        $this->logIn('ROLE_ADMIN');
        $crawler =  $this->client->request('GET', '/users');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('th:contains("Nom d\'utilisateur")')->count()
        );
    }

    public function testListUserAccessDenied()
    {
        $this->logIn('ROLE_USER');
        $crawler = $this->client->request('GET', '/users');
        // Test access denied
        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('body:contains("Access Denied")')->count()
        );
    }
}