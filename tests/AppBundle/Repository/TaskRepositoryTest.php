<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 16:33
 */

namespace Tests\AppBundle\Repository;

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

    public function testFindAllTasksDone()
    {
        $tasks = $this->em
            ->getRepository('AppBundle:Task')
            ->searchByIsDone()
        ;

        $this->assertCount(1, $tasks);
    }

    /**
     * {@inheritDoc}
     */
    /*protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}