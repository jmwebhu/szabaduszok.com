<?php

class Authorization_Role_User_Employer_Test extends Unittest_TestCase
{
    /**
     * @covers Authorization_User::canApply()
     */
    public function testCanApplyNotOk()
    {
        $user           = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $user);

        $this->assertFalse($authorization->canApply());
    }

    /**
     * @covers Authorization_User::canUndoApplication()
     */
    public function testUndoApplicationNotOk()
    {
        $user           = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $user);

        $this->assertFalse($authorization->canUndoApplication());
    }
}