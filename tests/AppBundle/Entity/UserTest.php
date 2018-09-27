<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 23/09/2018
 * Time: 23:13
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    private $task;

    public function setUp()
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testUserIsInstanceOfUserClass()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function testIdIsNull()
    {
        $this->assertNull($this->user->getId());
    }

    public function testUsernameIsOk()
    {
        $this->user->setUsername('username');
        $this->assertSame('username', $this->user->getUsername());
    }

    public function testGetSalt()
    {
        $this->assertSame(null, $this->user->getSalt());
    }

    public function testGetpassword()
    {
        $this->user->setPassword('password');
        $this->assertSame('password', $this->user->getPassword());
    }

    public function testGetEmail()
    {
        $this->user->setEmail('email');
        $this->assertSame('email', $this->user->getEmail());
    }

    public function testEraseCredentials()
    {
        $this->assertNull($this->user->eraseCredentials());
    }

    public function testGetRoles()
    {
        $roles = ['ROLE_USER'];
        $this->user->setRoles($roles);
        $this->assertSame($roles, $this->user->getRoles());
    }

    public function testAddTaskIsOk()
    {
        $this->user->addtask($this->task);
        $this->assertCount(1, $this->user->getTasks());
    }

    public function testRemovetaskIsOk()
    {
        $this->user->removeTask($this->task);
        $this->assertCount(0, $this->user->getTasks());
    }
}