<?php
/**
 * Created by PhpStorm.
 * User: philippe traon
 * Date: 13/10/2018
 * Time: 10:55
 */
namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

trait CreateTrait
{
    private $client;

    /**
     * Créer un utilisateur
     */
    public function createUser($role)
    {
        $user = new User;
        $user->setUsername('user'.random_int(1, 10000));
        $user->setEmail('email'.random_int(1, 10000).'@example.com');
        $user->setRole($role);
        $passwordEncoder = $this->getSecurityPasswordEncoder();
        $passwordEncode = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($passwordEncode);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->persist($user);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->flush();
        return $user;
    }

    /**
     * Créer une tâche en rapport avec l'utilisateur
     */
    public function createTask($user)
    {
        $task = new Task;
        $task->setTitle('Tâche'.random_int(1, 10000));
        $task->setContent('Contenu de la tâche de test.');
        $task->setUser($user);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->persist($task);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->flush();
        return $task;
    }
}