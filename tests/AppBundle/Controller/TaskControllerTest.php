<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 21:38
 */

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

class TaskControllerTest extends WebTestCase
{
    use LogTrait, CreateTrait;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testTaskListPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    public function testTaskListPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    public function testTaskToDoPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/todo');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/2/toggle"]')->count());
    }

    public function testTaskToDoPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/todo');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/2/toggle"]')->count());
    }

    public function testTaskDonePageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/done');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskDonePageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/done');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateDescPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateDescPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateAscPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateAscPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByAuthorPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/author');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByAuthorPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/author');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskCreate()
    {
        $crawler = $this->client->request('GET', '/tasks/create', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['task[title]'] = 'test test';
        $form['task[content]'] = 'test test';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("La tâche a été bien été ajoutée.")')->count());
    }

    public function testTaskEdit()
    {
        $crawler = $this->client->request('GET', '/tasks/2/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'test';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("modifiée.")')->count());
    }

    public function testTaskEdit2()
    {
        $crawler = $this->client->request('GET', '/tasks/2/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        //$crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("devez")')->count());
    }

    public function testTaskToggleOn()
    {
        $crawler = $this->client->request('GET', '/tasks/1/toggle', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $this->client->followRedirect();
        //$this->assertSame(302, $this->client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('html:contains("faite.")')->count());
    }

    public function testTaskToggleOff()
    {
        $crawler = $this->client->request('GET', '/tasks/1/toggle', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $this->client->followRedirect();
        //$this->assertSame(302, $this->client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('html:contains("faire.")')->count());
    }

    public function testTaskToggle2()
    {
        $crawler = $this->client->request('GET', '/tasks/1/toggle', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(1, $crawler->filter('html:contains("devez")')->count());
    }

    public function testTaskDelete()
    {
        $user = $this->logInAdmin();
        $task = $this->createTask($user);

        $this->client->request('GET', 'tasks/'. $task->getId() .'/delete');
        $response = $this->client->getResponse();
        $this->assertSame(302, $response->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("La tâche a bien été supprimée.")')->count());
    }

    public function testAnonymousTaskDeleteByAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/5/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(1, $crawler->filter('html:contains("La tâche a bien été supprimée.")')->count());
    }

    public function testTaskDeleteByBadAuthor()
    {
        $crawler = $this->client->request('GET', '/tasks/1/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(1, $crawler->filter('html:contains("devez")')->count());
    }

    public function tearDown()
    {
        $this->client = null;
    }
}