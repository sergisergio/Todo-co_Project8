<?php
/**
 * Created by PhpStorm.
 * User: philippetraon
 * Date: 27/09/2018
 * Time: 19:56
 */

namespace Tests\AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTypeTest extends TypeTestCase
{
    private $validator;
    /**
     * @return array
     */
    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata('Symfony\Component\Form\Form')));
        return array(
            new ValidatorExtension($this->validator)
        );
    }
    /**
     * Test the UserType form
     */
    public function testSubmitValidData()
    {
        $user = new User();
        $form = $this->factory->create(UserType::class, $user);
        $formData = array(
            'username' => 'username',
            'password' => array(
                'first' => 'password',
                'second' => 'password',
            ),
            'email' => 'email@email.fr',
            'roles' => ['ROLE_USER']
        );
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertSame($user, $form->getData());
        $this->assertSame('username', $user->getUsername());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('email@email.fr', $user->getEmail());
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}