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

class TaskControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'main';
        $token = new UsernamePasswordToken('admin', 'admin', $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testTaskListPageIsUp()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    public function testTaskToDoPageIsUp()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/todo');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/2/toggle"]')->count());
    }

    public function testTaskDonePageIsUp()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/done');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateDescPageIsUp()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByDateAscPageIsUp()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    public function testTaskByAuthorPageIsUp()
    {
        $this->logIn();
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

    public function tearDown()
    {
        $this->client = null;
    }
}