<?php

class Entity_User_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @covers Entity_User::getName()
     */
    public function testGetName()
    {
        $user = new Entity_User_Freelancer();
        $user->setLastname('Joó');
        $user->setFirstname('Martin');

        $user1 = new Entity_User_Freelancer();

        $this->assertEquals('Joó Martin', $user->getName());
        $this->assertEquals('Szabadúszó', $user1->getName());
    }
}