<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 21:38
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use LogTrait, CreateTrait;

    /**
     * Création du client HTTP
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test de la liste des tâches en tant qu'administrateur
     *
     * @dataProvider loadFixture
     */
    public function testTaskListPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    /**
     * Test de la liste des tâches en tant qu'utilisateur
     */
    public function testTaskListPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/create"]')->count());
    }

    /**
     * Test de la liste des tâches à faire en tant qu'administrateur
     */
    public function testTaskToDoPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/todo');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/2/toggle"]')->count());
    }

    /**
     * Test de la liste des tâches à faire en tant qu'utilisateur
     */
    public function testTaskToDoPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/todo');
        static::assertEquals(1, $crawler->filter('a[href="/tasks/2/toggle"]')->count());
    }

    /**
     * Test de la liste des tâches effectuées en tant qu'administrateur
     */
    public function testTaskDonePageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/done');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches effectuées en tant qu'utilisateur
     */
    public function testTaskDonePageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/done');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par date desc en tant qu'administrateur
     */
    public function testTaskByDateDescPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par date desc en tant qu'utilisateur
     */
    public function testTaskByDateDescPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/datedesc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par date asc en tant qu'administrateur
     */
    public function testTaskByDateAscPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par date asc en tant qu'utilisateur
     */
    public function testTaskByDateAscPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/dateasc');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par auteur en tant qu'administrateur
     */
    public function testTaskByAuthorPageAdminIsUp()
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks/author');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de la liste des tâches par auteur en tant qu'utilisateur
     */
    public function testTaskByAuthorPageUserIsUp()
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks/author');
        static::assertEquals(1, $crawler->filter('html:contains("Trier par")')->count());
    }

    /**
     * Test de création d'une tâche en tant qu'administrateur
     */
    public function testTaskCreateByAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/create', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'test';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("La tâche a été bien été ajoutée.")')->count());
    }

    /**
     * Test de création d'une tâche en tant qu'utilisateur
     */
    public function testTaskCreateByUser()
    {
        $crawler = $this->client->request('GET', '/tasks/create', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'test';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("La tâche a été bien été ajoutée.")')->count());
    }

    /**
     * Test de modification d'une tâche en tant qu'utilisateur
     */
    public function testTaskEditByUser()
    {
        $crawler = $this->client->request('GET', '/tasks/3/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'test';
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("modifiée.")')->count());
    }

    /**
     * Test de modification d'une tâche en tant qu'administrateur
     */
    public function testTaskEditByAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/1/edit', array(), array(), array(
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

    /**
     * Test de modification d'une tâche par un utilisateur ROLE_USER qui n'est pas l'auteur
     */
    public function testTaskEditByOtherUserRoleUser()
    {
        $crawler = $this->client->request('GET', '/tasks/2/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        //$crawler = $this->client->followRedirect();
        static::assertEquals(1, $crawler->filter('html:contains("devez")')->count());
    }

    /**
     * Test de modification d'une tâche par un utilisateur ROLE_ADMIN qui n'est pas l'auteur
     */
    public function testTaskEditByOtherUserRoleAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/3/edit', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        static::assertEquals(1, $crawler->filter('html:contains("devez")')->count());
    }

    /**
     * Test Lien Toggle pour marquer une tâche comme étant effectuée
     */
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

    /**
     * Test Lien Toggle pour marquer une tâche comme étant encore à faire
     */
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

    /**
     * Test de suppression d'une tâche par un administrateur
     */
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

    /**
     * Test de suppression d'une tâche d'un utilisateur anonyme par un administrateur
     */
    public function testAnonymousTaskDeleteByAdmin()
    {
        $crawler = $this->client->request('GET', '/tasks/5/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(1, $crawler->filter('html:contains("La tâche a bien été supprimée.")')->count());
    }

    /**
     * Test de suppression d'une tâche admin par u nutilisateur ROLE_USER
     */
    public function testTaskDeleteByBadAuthor()
    {
        $crawler = $this->client->request('GET', '/tasks/1/delete', array(), array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $this->client->followRedirect();
        static::assertSame(1, $crawler->filter('html:contains("devez")')->count());
    }

    public function loadFixture()
    {
        echo shell_exec('php bin/console doctrine:schema:drop --force --env=test');
        echo shell_exec('php bin/console doctrine:schema:create --env=test');
        echo shell_exec('php bin/console doctrine:fixtures:load  --no-interaction --env=test');
        return [
            [""]
        ];
    }

    public function tearDown()
    {
        $this->client = null;
    }
}