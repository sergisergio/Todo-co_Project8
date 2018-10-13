<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultControllerTest extends WebTestCase
{
    use LogTrait;

    /**
     * Création du client HTTP
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test de la page d'accueil après login d'un administrateur ROLE_ADMIN
     */
    public function testHomeByAdmin()
    {
        $this->logInAdmin();
        $this->client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test de la page d'accueil après login d'un utilisateur ROLE_USER
     */
    public function testHomeByUser()
    {
        $this->logInUser();
        $this->client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        $this->client = null;
    }
}