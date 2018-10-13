<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 22:40
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use LogTrait, CreateTrait;

    private $client;

    /**
     * Création client HTTP
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test d'affichage de la liste des utilisateurs par un administrateur ROLE_ADMIN
     */
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

    /**
     * Test d'affichage de la liste des utilisateurs par un utilisateur ROLE_USER
     */
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

    /**
     * Test d'affichage de la page d'ajout d'un utilisateur par un administrateur ROLE_ADMIN
     */
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

    /**
     * Test d'affichage de la page d'ajout d'un utilisateur par un utilisateur ROLE_USER
     */
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

    /**
     * Test d'ajout d'un utilisateur par un administrateur ROLE_AMIN
     */
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

    /**
     * Test de modification d'un utilisateur par un administrateur ROLE_ADMIN
     */
    public function testEditUserByAdmin()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/users/1/edit');
        static::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'user';
        $form['user[password][first]'] = 'userpassword';
        $form['user[password][second]'] = 'userpassword';
        $form['user[email]'] = 'test@test.com';
        $form['user[roles]'] = 'ROLE_USER';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('div.alert.alert-success:contains("modifié")')->count());
    }

    /**
     * Test de modification d'un utilisateur par un utilisateur ROLE_USER
     */
    public function testEditUserByUser()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/users/1/edit');
        static::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        $this->client = null;
    }
}