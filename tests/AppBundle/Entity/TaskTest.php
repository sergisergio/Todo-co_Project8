<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 23/09/2018
 * Time: 21:51
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    private $user;

    public function setUp()
    {
        $this->task = new Task();
        $this->user = new User();
    }

    public function testTaskIsInstanceOfTaskClass()
    {
        $this->assertInstanceOf(Task::class, $this->task);
    }

    public function testIdIsNull()
    {
        $this->assertNull($this->task->getId());
    }

    public function testSetCreatedAt()
    {
        $this->task->setCreatedAt(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $this->task->getCreatedAt());
    }

    public function testTitleIsOk()
    {
        $this->task->setTitle('title');
        $this->assertSame('title', $this->task->getTitle());
    }

    public function testContentIsOk()
    {
        $this->task->setContent('content');
        $this->assertSame('content', $this->task->getContent());
    }

    public function testIsDoneIsOk()
    {
        $this->task->setIsDone('true');
        $this->assertSame('true', $this->task->getIsDone());
    }

    public function testIsDoneIsFalse()
    {
        $this->assertFalse($this->task->isDone());
    }

    public function testToggle()
    {
        $this->task->toggle(true);
        $this->assertEquals(true, $this->task->isDone());
    }

    public function testUser()
    {

        $this->task->setUser($this->user);
        $this->assertEquals($this->user, $this->task->getUser());
    }
}