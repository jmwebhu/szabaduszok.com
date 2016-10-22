<?php

class Entity_User_Employer_Test extends Unittest_TestCase
{
    /**
     * @covers Entity_User_Freelancer::__construct()
     */
    public function testConstructWithoutValue()
    {
        $entity = new Entity_User_Employer();

        $this->invokeMethod($entity, '__construct', []);

        $this->assertTrue($entity->getModel() instanceof Model_User_Employer);
        $this->assertTrue($entity->getFile() instanceof File_User_Employer);
        $this->assertTrue($entity->getBusiness() instanceof Business_User_Employer);
    }
}