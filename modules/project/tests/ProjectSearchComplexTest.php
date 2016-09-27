<?php

class ProjectSearchComplexTest extends Unittest_TestCase
{
    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchSkillsInProjects()
     */
    public function testSearchSkillsInProjectsOrOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';

        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $projectSkills = [
            1 => [
                1, 2, 3, 8, 10
            ],
            2 => [
                1, 8, 10
            ],
            3 => [
                1
            ],
            4 => [
                3
            ],
            5 => []
        ];

        $projectSkill  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkill->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills));

        $projects = $this->invokeMethod(
            $complexSearch,
            'searchSkillsInProjects',
            [[$project1, $project2, $project3, $project4, $project5], $skills, Project_Search_Complex::SKILL_RELATION_OR, $projectSkill]);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
        $this->assertTrue(in_array(4, $ids));
        $this->assertTrue(in_array(5, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchSkillsInProjects()
     */
    public function testSearchSkillsInProjectsOrNotOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $skills   = [1, 3];

        $projectSkills = [
            1 => [
                2, 8, 10
            ],
            2 => [
                8
            ]
        ];

        $projectSkill  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkill->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills));

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $projects = $this->invokeMethod(
            $complexSearch,
            'searchSkillsInProjects',
            [[$project1, $project2], $skills, Project_Search_Complex::SKILL_RELATION_OR, $projectSkill]);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchSkillsInProjects()
     */
    public function testSearchSkillsInProjectsAndOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';

        $project6 = new Model_Project();
        $project6->project_id = 6;
        $project6->name = 'sixth';

        $projectSkills = [
            1 => [
                1, 2, 3, 8, 10
            ],
            5 => [],
            6 => [
                1, 3
            ]
        ];

        $projectSkill  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkill->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills));

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $projects = $this->invokeMethod(
            $complexSearch,
            'searchSkillsInProjects',
            [[$project1, $project5, $project6], $skills, Project_Search_Complex::SKILL_RELATION_AND, $projectSkill]);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(5, $ids));
        $this->assertTrue(in_array(6, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchSkillsInProjects()
     */
    public function testSearchSkillsInProjectsAndNotOk()
    {
        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';

        $projectSkills = [
            2 => [
                1, 8, 10
            ],
            3 => [
                1
            ],
            4 => [
                3
            ]
        ];

        $projectSkill  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkill->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills));


        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $projects = $this->invokeMethod(
            $complexSearch,
            'searchSkillsInProjects',
            [[$project2, $project3, $project4], $skills, Project_Search_Complex::SKILL_RELATION_AND, $projectSkill]);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));
        $this->assertFalse(in_array(4, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $industry1 = new Model_Industry();
        $industry1->industry_id = 1;

        $industry2 = new Model_Industry();
        $industry2->industry_id = 2;

        $industry3 = new Model_Industry();
        $industry3->industry_id = 3;

        $industry4 = new Model_Industry();
        $industry4->industry_id = 4;

        $industry5 = new Model_Industry();
        $industry5->industry_id = 5;

        $industry6 = new Model_Industry();
        $industry6->industry_id = 6;

        $projectIndustries = [
            1 => [
                $industry1, $industry2
            ],
            2 => [
                $industry3, $industry4
            ],
            3 => [
                $industry5, $industry6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectIndustries));

        $industries = [1, 3, 4];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true, 'industries' => $industries]);
        $complexSearch->setMatchedProjects([$project1, $project2, $project3]);

        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [$projectIndustry1]);
        var_dump($projects);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        /*$industries = [6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true, ['industries' => $industries]]);
        $complexSearch->setMatchedProjects([$project1, $project2, $project3]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [$projectIndustry1]);

        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));

        $industries = [7, 1, 8, 6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true, 'industries' => $industries]);
        $complexSearch->setMatchedProjects([$project1, $project2, $project3]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [$projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));*/
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNotOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectIndustries = [
            1 => [
                1, 2
            ],
            2 => [
                3, 4
            ],
            3 => [
                5, 6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectIndustries));

        $industries = [10];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $industries = [7, 11];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNoPostOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectIndustries = [
            1 => [
                1, 2
            ],
            2 => [
                3, 4
            ],
            3 => [
                5, 6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectIndustries));

        $industries = [];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectProfessions = [
            1 => [
                1, 2
            ],
            2 => [
                3, 4
            ],
            3 => [
                5, 6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Profession')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectProfessions));

        $professions = [1, 3, 4];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $professions = [6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));

        $professions = [7, 1, 8, 6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionNotOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectProfessions = [
            1 => [
                1, 2
            ],
            2 => [
                3, 4
            ],
            3 => [
                5, 6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectProfessions));

        $professions = [10];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $professions = [7, 11];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsNoPostOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectProfessions = [
            1 => [
                1, 2
            ],
            2 => [
                3, 4
            ],
            3 => [
                5, 6
            ]
        ];

        $projectIndustry1  = $this->getMockBuilder('\Model_Project_Industry')->getMock();
        $projectIndustry1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectProfessions));

        $professions = [];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchRelationsInProjects', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
    }
}