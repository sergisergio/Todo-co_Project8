<?php
namespace tests\AppBundle\Controller;


use AppBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

Class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        static::assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testLogin()
    {
        $securityController = new SecurityController();
        static::assertNull($securityController->loginCheck());
    }
    public function testLogout()
    {
        $securityController = new SecurityController();
        static::assertNull($securityController->logoutCheck());
    }
}