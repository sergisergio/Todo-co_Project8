<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 23/09/2018
 * Time: 21:51
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends testCase
{
    private $task;

    public function setUp()
    {
        $this->task = new Task();
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

    public function testIsDoneIsFalse()
    {
        $this->assertFalse($this->task->isDone());
    }
}