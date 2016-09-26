<?php

class ProjectSearchComplexTest extends Unittest_TestCase
{
    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchBySkillsAndSkillRelation()
     */
    public function testSearchBySkillsAndSkillRelationOrOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                1, 2, 3, 8, 10
            ]
        ];

        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                1, 8, 10
            ]
        ];

        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectSkill3  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills3 = [
            3 => [
                1
            ]
        ];

        $projectSkill3->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills3));

        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';

        $projectSkill4  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills4 = [
            4 => [
                3
            ]
        ];

        $projectSkill4->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills4));

        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';

        $projectSkill5  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills5 = [
            5 => []
        ];

        $projectSkill5->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills5));

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project1, $skills, $projectSkill1, Project_Search_Complex::SKILL_RELATION_OR]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project2, $skills, $projectSkill2, Project_Search_Complex::SKILL_RELATION_OR]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project3, $skills, $projectSkill3, Project_Search_Complex::SKILL_RELATION_OR]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project4, $skills, $projectSkill4, Project_Search_Complex::SKILL_RELATION_OR]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project5, $skills, $projectSkill5, Project_Search_Complex::SKILL_RELATION_OR]));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchBySkillsAndSkillRelation()
     */
    public function testSearchBySkillsAndSkillRelationOrNotOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                2, 8, 10
            ]
        ];

        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));

        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                8
            ]
        ];

        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $this->assertFalse($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project1, $skills, $projectSkill1, Project_Search_Complex::SKILL_RELATION_OR]));
        $this->assertFalse($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project2, $skills, $projectSkill2, Project_Search_Complex::SKILL_RELATION_OR]));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchBySkillsAndSkillRelation()
     */
    public function testSearchBySkillsAndSkillRelationAndOk()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;
        $project1->name = 'first';

        $projectSkill1  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills1 = [
            1 => [
                1, 2, 3, 8, 10
            ]
        ];

        $projectSkill1->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills1));

        $project5 = new Model_Project();
        $project5->project_id = 5;
        $project5->name = 'third';

        $projectSkill5  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills5 = [
            5 => []
        ];

        $projectSkill5->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills5));

        $project6 = new Model_Project();
        $project6->project_id = 6;
        $project6->name = 'sixth';

        $projectSkill6  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills6 = [
            6 => [
                1, 3
            ]
        ];

        $projectSkill6->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills6));

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project1, $skills, $projectSkill1, Project_Search_Complex::SKILL_RELATION_AND]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project5, $skills, $projectSkill5, Project_Search_Complex::SKILL_RELATION_AND]));
        $this->assertTrue($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project6, $skills, $projectSkill6, Project_Search_Complex::SKILL_RELATION_AND]));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchBySkillsAndSkillRelation()
     */
    public function testSearchBySkillsAndSkillRelationAndNotOk()
    {
        $project2 = new Model_Project();
        $project2->project_id = 2;
        $project2->name = 'second';

        $projectSkill2  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills2 = [
            2 => [
                1, 8, 10
            ]
        ];

        $projectSkill2->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills2));

        $project3 = new Model_Project();
        $project3->project_id = 3;
        $project3->name = 'third';

        $projectSkill3  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills3 = [
            3 => [
                1
            ]
        ];

        $projectSkill3->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills3));

        $project4 = new Model_Project();
        $project4->project_id = 4;
        $project4->name = 'third';

        $projectSkill4  = $this->getMockBuilder('\Model_Project_Skill')->getMock();
        $projectSkills4 = [
            4 => [
                3
            ]
        ];

        $projectSkill4->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($projectSkills4));

        $skills   = [1, 3];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);

        $this->assertFalse($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project2, $skills, $projectSkill2, Project_Search_Complex::SKILL_RELATION_AND]));
        $this->assertFalse($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project3, $skills, $projectSkill3, Project_Search_Complex::SKILL_RELATION_AND]));
        $this->assertFalse($this->invokeMethod($complexSearch, 'searchBySkillsAndSkillRelation', [$project4, $skills, $projectSkill4, Project_Search_Complex::SKILL_RELATION_AND]));
    }

    /**
     * @group complexSearch
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationIndustryOk()
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

        $industries = [1, 3, 4];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $industries = [6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));

        $industries = [7, 1, 8, 6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
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
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationIndustryNotOk()
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
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $industries = [7, 11];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
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
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationIndustryNoPostOk()
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
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $industries, $projectIndustry1]);
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
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationProfessionOk()
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
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $professions = [6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));

        $professions = [7, 1, 8, 6];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
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
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationProfessionNotOk()
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
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertFalse(in_array(1, $ids));
        $this->assertFalse(in_array(2, $ids));
        $this->assertFalse(in_array(3, $ids));

        $professions = [7, 11];

        $complexSearch = Project_Search_Factory::getAndSetSearch(['complex' => true]);
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
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
     * @covers Project_Search_Complex::searchByRelation()
     */
    public function testSearchByRelationProfessionNoPostOk()
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
        $projects = $this->invokeMethod($complexSearch, 'searchByRelation', [[$project1, $project2, $project3], $professions, $projectIndustry1]);
        $ids = [];

        foreach ($projects as $project) {
            $ids[] = $project->project_id;
        }

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
    }
}