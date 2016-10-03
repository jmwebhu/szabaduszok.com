<?php

class AuthorozationProjectTest extends Unittest_TestCase
{
    /**
     * @var Authorization_Project
     */
    private $_authorization;

    /**
     * @var Model_User
     */
    private $_admin;

    private $_freelancers   = [];
    private $_employers     = [];
    private $_projects      = [];

    /**
     * @covers Authorization_Project::canCreate()
     */
    public function testCanCreateOk()
    {
        $this->_authorization = new Authorization_Project(null, $this->_employers[0]);
        $this->assertTrue($this->_authorization->canCreate());

        $this->_authorization = new Authorization_Project(null, $this->_employers[1]);
        $this->assertTrue($this->_authorization->canCreate());

        $this->_authorization = new Authorization_Project(null, $this->_admin);
        $this->assertTrue($this->_authorization->canCreate());
    }

    /**
     * @covers Authorization_Project::canCreate()
     */
    public function testCanCreateNotOk()
    {
        $this->_authorization = new Authorization_Project(null, $this->_freelancers[0]);
        $this->assertFalse($this->_authorization->canCreate());

        $this->_authorization = new Authorization_Project(null, $this->_freelancers[1]);
        $this->assertFalse($this->_authorization->canCreate());
    }

    public function setUp()
    {
        $this->_admin = new Model_User();
        $this->_admin->firstname = 'Admin';
        $this->_admin->lastname = 'Admin';
        $this->_admin->user_id = 1;
        $this->_admin->is_admin = 1;

        $freelancer = new Model_User();
        $freelancer->user_id = 2;
        $freelancer->type = 1;
        $freelancer->firstname = 'Freelancer2';

        $this->_freelancers[] = $freelancer;

        $freelancer->type = 1;
        $freelancer->user_id = 3;
        $freelancer->firstname = 'Freelancer3';

        $this->_freelancers[] = $freelancer;

        $employer = new Model_User();
        $employer->user_id = 4;
        $employer->type = 2;
        $employer->firstname = 'Employer4';

        $this->_employers[] = $employer;

        $employer->user_id = 5;
        $employer->type = 2;
        $employer->firstname = 'Employer5';

        $this->_employers[] = $employer;

        $project = new Model_Project();
        $project->project_id = 1;
        $project->user_id = 4;

        $this->_projects[] = $project;

        $project->project_id = 2;
        $project->user_id = 4;
        $project->is_active = 0;

        $this->_projects[] = $project;

        $project->project_id = 3;
        $project->user_id = 5;
        $project->is_active = 0;

        $this->_projects[] = $project;

        parent::setUp();
    }
}