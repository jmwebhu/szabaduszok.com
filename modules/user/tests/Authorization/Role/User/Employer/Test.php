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

    /**
     * @covers Authorization_Role_User_Employer::canApproveApplication()
     */
    public function testCanApproveApplicationOk()
    {
        $user               = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $user->user_id      = 10000;
        $project            = new Model_Project();
        $project->user_id   = 10000;

        $authorization  = new Authorization_User($project, $user);

        $this->assertTrue($authorization->canApproveApplication());
    }

    /**
     * @covers Authorization_Role_User_Employer::canApproveApplication()
     */
    public function testCanApproveApplicationNotOk()
    {
        $user               = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $user->user_id      = 10001;
        $project            = new Model_Project();
        $project->user_id   = 10000;

        $authorization  = new Authorization_User($project, $user);

        $this->assertFalse($authorization->canApproveApplication());
    }

    /**
     * @covers Authorization_Role_User_Employer::canRejectApplication()
     */
    public function testCanRejectApplicationOk()
    {
        $user               = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $user->user_id      = 10000;
        $project            = new Model_Project();
        $project->user_id   = 10000;

        $authorization  = new Authorization_User($project, $user);

        $this->assertTrue($authorization->canRejectApplication());
    }

    /**
     * @covers Authorization_Role_User_Employer::canRejectApplication()
     */
    public function testCanRejectApplicationNotOk()
    {
        $user               = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $user->user_id      = 10001;
        $project            = new Model_Project();
        $project->user_id   = 10000;

        $authorization  = new Authorization_User($project, $user);

        $this->assertFalse($authorization->canRejectApplication());
    }
}