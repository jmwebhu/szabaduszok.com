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
    public function testCanCreateEmployerOk()
    {
        $this->_authorization = new Authorization_Project(null, $this->_employers[0]);
        $this->assertTrue($this->_authorization->canCreate());

        $this->_authorization = new Authorization_Project(null, $this->_employers[1]);
        $this->assertTrue($this->_authorization->canCreate());
    }

    /**
     * @covers Authorization_Project::canCreate()
     */
    public function testCanCreateAdminOk()
    {
        $this->_authorization = new Authorization_Project(null, $this->_admin);
        $this->assertTrue($this->_authorization->canCreate());
    }

    /**
     * @covers Authorization_Project::canCreate()
     */
    public function testCanCreateFreelancerNotOk()
    {
        $this->_authorization = new Authorization_Project(null, $this->_freelancers[0]);
        $this->assertFalse($this->_authorization->canCreate());

        $this->_authorization = new Authorization_Project(null, $this->_freelancers[1]);
        $this->assertFalse($this->_authorization->canCreate());
    }

    /**
     * @covers Authorization_Project::canEdit()
     */
    public function testCanEditAdminOk()
    {
        foreach ($this->_projects as $project) {
            $this->_authorization = new Authorization_Project($project, $this->_admin);
            $this->assertTrue($this->_authorization->canEdit());
        }
    }

    /**
     * @covers Authorization_Project::canEdit()
     */
    public function testCanEditEmployerOwnerOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[0], $this->_employers[0]);
        $this->assertTrue($this->_authorization->canEdit());

        $this->_authorization = new Authorization_Project($this->_projects[2], $this->_employers[1]);
        $this->assertTrue($this->_authorization->canEdit());
    }

    /**
     * @covers Authorization_Project::canEdit()
     */
    public function testCanEditEmployerInactiveProjectNotOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[1], $this->_employers[0]);
        $this->assertFalse($this->_authorization->canEdit());
    }

    /**
     * @covers Authorization_Project::canEdit()
     */
    public function testCanEditEmployerNotOwnerProjectNotOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[2], $this->_employers[0]);
        $this->assertFalse($this->_authorization->canEdit());
    }

    /**
     * @covers Authorization_Project::canEdit()
     */
    public function testCanEditFreelancerNotOk()
    {
        foreach ($this->_projects as $project) {
            foreach ($this->_freelancers as $freelancer) {
                $this->_authorization = new Authorization_Project($project, $freelancer);
                $this->assertFalse($this->_authorization->canEdit());
            }
        }
    }

    /**
     * @covers Authorization_Project::canDelete()
     */
    public function testCanDeleteAdminOk()
    {
        foreach ($this->_projects as $project) {
            $this->_authorization = new Authorization_Project($project, $this->_admin);
            $this->assertTrue($this->_authorization->canDelete());
        }
    }

    /**
     * @covers Authorization_Project::canDelete()
     */
    public function testCanDeleteEmployerOwnerOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[0], $this->_employers[0]);
        $this->assertTrue($this->_authorization->canDelete());

        $this->_authorization = new Authorization_Project($this->_projects[2], $this->_employers[1]);
        $this->assertTrue($this->_authorization->canDelete());
    }

    /**
     * @covers Authorization_Project::canDelete()
     */
    public function testCanDeleteEmployerInactiveProjectNotOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[1], $this->_employers[0]);
        $this->assertFalse($this->_authorization->canDelete());

        $this->_authorization = new Authorization_Project($this->_projects[3], $this->_employers[1]);
        $this->assertFalse($this->_authorization->canDelete());
    }

    /**
     * @covers Authorization_Project::canDelete()
     */
    public function testCanDeleteEmployerNotOwnerProjectNotOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[2], $this->_employers[0]);
        $this->assertFalse($this->_authorization->canDelete());

        $this->_authorization = new Authorization_Project($this->_projects[0], $this->_employers[1]);
        $this->assertFalse($this->_authorization->canDelete());
    }

    /**
     * @covers Authorization_Project::canDelete()
     */
    public function testCanDeleteFreelancerNotOk()
    {
        foreach ($this->_projects as $project) {
            foreach ($this->_freelancers as $freelancer) {
                $this->_authorization = new Authorization_Project($project, $freelancer);
                $this->assertFalse($this->_authorization->canDelete());
            }
        }
    }

    /**
     * @covers Authorization_Project::hasCancel()
     */
    public function testHasCancelAdminOk()
    {
        foreach ($this->_projects as $project) {
            $this->_authorization = new Authorization_Project($project, $this->_admin);
            $this->assertTrue($this->_authorization->hasCancel());
        }
    }

    /**
     * @covers Authorization_Project::hasCancel()
     */
    public function testHasCancelEmployerOwnerOk()
    {
        $this->_authorization = new Authorization_Project($this->_projects[0], $this->_employers[0]);
        $this->assertTrue($this->_authorization->hasCancel());

        $this->_authorization = new Authorization_Project($this->_projects[2], $this->_employers[1]);
        $this->assertTrue($this->_authorization->hasCancel());
    }

    /**
     * @covers Authorization_Project::hasCancel()
     */
    public function testHasCancelFreelancerNotOk()
    {
        foreach ($this->_projects as $project) {
            foreach ($this->_freelancers as $freelancer) {
                $this->_authorization = new Authorization_Project($project, $freelancer);
                $this->assertFalse($this->_authorization->hasCancel());
            }
        }
    }

    public function setUp()
    {
        $this->_admin = new Model_User();
        $this->_admin->firstname = 'Admin';
        $this->_admin->lastname = 'Admin';
        $this->_admin->user_id = 1;
        $this->_admin->is_admin = 1;

        $freelancer2 = new Model_User();
        $freelancer2->user_id = 2;
        $freelancer2->type = 1;
        $freelancer2->firstname = 'Freelancer2';

        $this->_freelancers[] = $freelancer2;

        $freelancer3 = new Model_User();
        $freelancer3->type = 1;
        $freelancer3->user_id = 3;
        $freelancer3->firstname = 'Freelancer3';

        $this->_freelancers[] = $freelancer3;

        $employer4 = new Model_User();
        $employer4->user_id = 4;
        $employer4->type = 2;
        $employer4->firstname = 'Employer4';

        $this->_employers[] = $employer4;

        $employer5 = new Model_User();
        $employer5->user_id = 5;
        $employer5->type = 2;
        $employer5->firstname = 'Employer5';

        $this->_employers[] = $employer5;

        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->user_id = 4;
        $project1->is_active = 1;

        $this->_projects[] = $project1;

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->user_id = 4;
        $project2->is_active = 0;

        $this->_projects[] = $project2;

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->user_id = 5;
        $project3->is_active = 1;

        $this->_projects[] = $project3;

        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->user_id = 5;
        $project4->is_active = 0;

        $this->_projects[] = $project4;

        parent::setUp();
    }
}