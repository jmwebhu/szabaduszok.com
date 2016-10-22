<?php

class Model_User_Test extends Unittest_TestCase
{
    /**
     * @covers Model_User::createUser()
     */
    public function testCreateUserEmployer()
    {
        $user = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $this->assertTrue($user instanceof Model_User_Employer);
    }

    /**
     * @covers Model_User::createUser()
     */
    public function testCreateUserFreelancer()
    {
        $user = Model_User::createUser(Entity_User::TYPE_FREELANCER);
        $this->assertTrue($user instanceof Model_User_Freelancer);
    }
}