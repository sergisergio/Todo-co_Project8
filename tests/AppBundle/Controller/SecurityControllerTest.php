<?php
namespace tests\AppBundle\Controller;

if (!class_exists('PHPUnit_Framework_TestCase') && class_exists('PHPUnit\Framework\TestCase')) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
Class SecurityControllerTest extends WebTestCase
{
    public function testLoginShow()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}