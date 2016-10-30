<?php

class Viewhelper_User_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_User_Factory::createViewhelper()
     */
    public function testCreateViewhelperFreelancerCreate()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);
        $type = $viewhelper->getType();

        $this->assertTrue($type instanceof Viewhelper_User_Type_Freelancer_Create);
    }

    /**
     * @covers Viewhelper_User_Factory::createViewhelper()
     */
    public function testCreateViewhelperFreelancerEdit()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);
        $type = $viewhelper->getType();

        $this->assertTrue($type instanceof Viewhelper_User_Type_Freelancer_Edit);
    }

    /**
     * @covers Viewhelper_User_Factory::createViewhelper()
     */
    public function testCreateViewhelperEmployerCreate()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);
        $type = $viewhelper->getType();

        $this->assertTrue($type instanceof Viewhelper_User_Type_Employer_Create);
    }

    /**
     * @covers Viewhelper_User_Factory::createViewhelper()
     */
    public function testCreateViewhelperEmployerEdit()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);
        $type = $viewhelper->getType();

        $this->assertTrue($type instanceof Viewhelper_User_Type_Employer_Edit);
    }
}