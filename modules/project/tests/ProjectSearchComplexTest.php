<?php

class ProjectSearchComplexTest extends Unittest_TestCase
{
    private $_projects      = [];
    private $_matchedProjects = [];

    private $_industries    = [];
    private $_professions   = [];
    private $_skills        = [];

    private $_projectIndustries = [];

    public function setUp()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;

        $project2 = new Model_Project();
        $project2->project_id = 2;

        $project3 = new Model_Project();
        $project3->project_id = 3;

        $project4 = new Model_Project();
        $project4->project_id = 4;

        $project5 = new Model_Project();
        $project5->project_id = 5;

        $industry1 = new Model_Industry();
        $industry1->industry_id = 1;

        $industry2 = new Model_Industry();
        $industry2->industry_id = 2;

        $industry3 = new Model_Industry();
        $industry3->industry_id = 3;

        $this->_projects = [
            $project1, $project2, $project3, $project4, $project5
        ];

        $this->_industries = [
            $industry1, $industry2, $industry3
        ];

        $this->_projectIndustries = [
            1 => [
                $industry1, $industry2
            ],
            2 => [
                $industry2
            ],
            3 => [
                $industry1
            ],
            4 => [
                $industry2, $industry3
            ],
            5 => [
                $industry1, $industry2, $industry3
            ]
        ];

        parent::setUp();
    }

    public function setMatchedProjectIdsFromSearch(Project_Search_Complex $search)
    {
        $projects = $search->getMatchedProjects();

        foreach ($projects as $project) {
            $this->_matchedProjects[] = $project->project_id;
        }
    }

    public function testSearchRelationsInProjectsIndustryOk()
    {
        $projectIndustryMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();

        $projectIndustryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectIndustries));

        $industries = [1];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));

        $industries = [1, 3];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertTrue(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));

        $industries = [1, 3, 2];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertTrue(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertTrue(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));
    }

    public function testSearchRelationsInProjectsIndustryNotOk()
    {
        $projectIndustryMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();

        $projectIndustryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectIndustries));

        $industries = [4];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $industries = [4, 11];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));
    }

    public function testSearchRelationsInProjectsIndustryNoPostOk()
    {
        $projectIndustryMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();

        $projectIndustryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectIndustries));

        $industries = [];
        $search = Project_Search_Factory::getAndSetSearch(['industries' => $industries, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertTrue(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertTrue(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));
    }
}