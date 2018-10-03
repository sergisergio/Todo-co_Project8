<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 21:38
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
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
            $crawler = $this->client->request('GET', '/login');
            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user';
            $form['_password'] = 'user';
            $this->client->submit($form);
            return;
        }
        if ($role == 'ROLE_ADMIN') {
            $crawler = $this->client->request('GET', '/login');
            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'admin';
            $form['_password'] = 'admin';
            $this->client->submit($form);
        }
    }

    public function testCreateTask()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/create');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        // Form submit
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre';
        $form['task[content]'] = 'Contenu';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count());
    }

    public function testTaskToDo()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/todo');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskDone()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/done');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskDateDesc()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskDateAsc()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testTaskAuthor()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/author');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'user'
        ]);
        $crawler = $this->client->request('GET', '/tasks/create');
        $buttonCrawlerAddTask = $crawler->selectButton('Ajouter');
        $formTask = $buttonCrawlerAddTask->form();
        $this->client->submit($formTask, [
            'task[title]' => 'titre',
            'task[content]' => 'contenu'
        ]);
        static::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'user'
        ]);
        $crawler = $this->client->request('GET', '/tasks/1/edit');
        static::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testToggle()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerForm = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerForm->form();
        $this->client->submit($form, [
            '_username' => 'user',
            '_password' => 'user'
        ]);
        $crawler = $this->client->request('GET', '/tasks/1/toggle');
        static::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}