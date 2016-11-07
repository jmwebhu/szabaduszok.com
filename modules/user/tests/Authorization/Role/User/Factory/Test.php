<?php

class Authorization_Role_User_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Authorization_Role_User_Factory::createRole()
     */
    public function testCreateRoleFreelancer()
    {
        $freelancer     = Model_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer->type = Entity_User::TYPE_FREELANCER;

        $authFreelancer = new Authorization_User($freelancer, $freelancer);

        $role = Authorization_Role_User_Factory::createRole($authFreelancer);
        $this->assertTrue($role instanceof Authorization_Role_User_Freelancer);
    }

    /**
     * @covers Authorization_Role_User_Factory::createRole()
     */
    public function testCreateRoleEmployer()
    {
        $employer       = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $employer->type = Entity_User::TYPE_EMPLOYER;
        $authEmployer   = new Authorization_User($employer, $employer);

        $role = Authorization_Role_User_Factory::createRole($authEmployer);
        $this->assertTrue($role instanceof Authorization_Role_User_Employer);
    }

    /**
     * @covers Authorization_Role_User_Factory::createRole()
     */
    public function testCreateRoleAdmin()
    {
        $employer           = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $employer->type     = Entity_User::TYPE_EMPLOYER;
        $employer->is_admin = true;
        $authEmployer       = new Authorization_User($employer, $employer);

        $role = Authorization_Role_User_Factory::createRole($authEmployer);
        $this->assertTrue($role instanceof Authorization_Role_User_Admin);
    }
}