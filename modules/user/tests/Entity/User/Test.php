<?php

class Entity_User_Test extends Unittest_TestCase
{
    /**
     * @covers Entity_User::getName()
     */
    public function testGetName()
    {
        $user = new Entity_User_Employer();
        $user->setLastname('Joó');
        $user->setFirstname('Martin');

        $this->assertEquals('Joó Martin', $user->getName());
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1,
            'password'  => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => '',
            'passrod'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => null,
            'passrod'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::createUser()
     */
    public function testCreateUserOk()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $this->assertTrue($user instanceof Entity_User_Freelancer);

        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $this->assertTrue($user instanceof Entity_User_Employer);
    }

    /**
     * @covers Entity_User::createUser()
     * @expectedException Exception
     */
    public function testCreateUserNotOk()
    {
        Entity_User::createUser(9912);
    }
}