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

    public function testListAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'admin',
            '_password' => 'admin'
        ]);
        $this->client->request('GET', '/users');
        static::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListAsUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'user'
        ]);
        $this->client->request('GET', '/users');
        static::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'admin',
            '_password' => 'admin'
        ]);
        $this->client->request('GET', '/users/create');
        static::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionAsUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'user'
        ]);
        $this->client->request('GET', '/users/create');
        static::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'admin',
            '_password' => 'admin'
        ]);
        $crawler = $this->client->request('GET', '/users/create');
        $buttonCrawlerAddUser = $crawler->selectButton('Ajouter');
        $formUser = $buttonCrawlerAddUser->form();
        $this->client->submit($formUser, [
            'user[username]' => 'username'.rand(0, 10000),
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => rand(0, 10000).'email@gmail.com',
            'user[roles]' => 'ROLE_ADMIN'
        ]);
        static::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'admin',
            '_password' => 'admin'
        ]);
        $crawler = $this->client->request('GET', '/users/1/edit');
        static::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}