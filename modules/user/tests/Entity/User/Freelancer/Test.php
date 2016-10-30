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

    /**
     * @covers Entity_User_Freelancer::__construct()
     */
    public function testConstructWithoutValue()
    {
        $entity = new Entity_User_Freelancer();

        $this->invokeMethod($entity, '__construct', []);

        $this->assertTrue($entity->getModel() instanceof Model_User_Freelancer);
        $this->assertTrue($entity->getFile() instanceof File_User_Freelancer);
        $this->assertTrue($entity->getBusiness() instanceof Business_User_Freelancer);
    }
}