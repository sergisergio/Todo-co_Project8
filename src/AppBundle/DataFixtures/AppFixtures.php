<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 02/10/2018
 * Time: 10:21
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Users
        $user = new User();
        $user->setUsername('user');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $user->setEmail('user@user.fr ');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setEmail('admin@admin.fr ');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $anonymous = new User();
        $anonymous->setUsername(null);
        $anonymous->setPassword('');
        $anonymous->setEmail('');
        $anonymous->setRoles(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $manager->persist($anonymous);

        // Tasks

        // Admin
        $task1 = new Task();
        $task1->setTitle('Task 1 Admin');
        $task1->setContent('Content 1 Admin');
        $task1->setUser($admin);
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle('Task 2 Admin');
        $task2->setContent('Content 2 Admin');
        $task2->setUser($admin);
        $manager->persist($task2);

        // User
        $task3 = new Task();
        $task3->setTitle('Task 3 User');
        $task3->setContent('Content 3 User');
        $task3->setUser($user);
        $manager->persist($task3);

        $task4 = new Task();
        $task4->setTitle('Task 4 User');
        $task4->setContent('Content 4 User');
        $task4->setUser($user);
        $manager->persist($task4);

        // Anonymous
        $task5 = new Task();
        $task5->setTitle('Task 5 Anonymous');
        $task5->setContent('Content 5 Anonymous');
        $task5->setUser($anonymous);
        $manager->persist($task5);

        $task6 = new Task();
        $task6->setTitle('Task 6 Anonymous');
        $task6->setContent('Content 6 Anonymous');
        $task6->setUser($anonymous);
        $manager->persist($task6);


        $manager->flush();
    }
}