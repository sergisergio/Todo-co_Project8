<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 16:33
 */

namespace Tests\AppBundle\Repository;

if (!class_exists('PHPUnit_Framework_TestCase') && class_exists('PHPUnit\Framework\TestCase')) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/*class TaskRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    /*private $em;

    /**
     * {@inheritDoc}
     */
    /*protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /*public function testFindAllTaskToDo()
    {
        $tasks = $this->em
            ->getRepository('AppBundle:Task')
            ->findAllTasksTodo();

        $this->assertNotEmpty('content', $tasks
        );
    }*/

    /*public function testFindAllTasksDone()
    {
        $tasks = $this->em
            ->getRepository('AppBundle:Task')
            ->findAllTasksDone('2');

        $this->assertCount(2, $tasks
        );
    }*/

    /*/**
     * {@inheritDoc}
     */
    /*protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}*/