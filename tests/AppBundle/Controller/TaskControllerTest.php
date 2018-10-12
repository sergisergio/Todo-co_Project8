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
        static::assertEquals(1, $crawler->filter('a[href="/tasks/1/toggle"]')->count());
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

    public function tearDown()
    {
        $this->client = null;
    }
}