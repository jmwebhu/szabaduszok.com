<?php

class ProjectSearchComplexTest extends Unittest_TestCase
{
    private $_projects      = [];
    private $_matchedProjects = [];

    private $_industries    = [];
    private $_professions   = [];
    private $_skills        = [];

    private $_projectIndustries = [];
    private $_projectProfessions = [];
    private $_projectSkills = [];

    public function setUp()
    {
        for ($i = 1; $i < 6; $i++) {
            $project = new Model_Project();
            $project->project_id = $i;

            $this->_projects[] = $project;
        }

        for ($i = 1; $i < 4; $i++) {
            $industry = new Model_Industry();
            $industry->industry_id = $i;

            $this->_industries[] = $industry;
        }

        for ($i = 1; $i < 5; $i++) {
            $profession = new Model_Profession();
            $profession->profession_id = $i;

            $this->_professions[] = $profession;
        }

        for ($i = 1; $i < 6; $i++) {
            $skill = new Model_Skill();
            $skill->skill_id = $i;

            $this->_skills[] = $skill;
        }

        $this->_projectIndustries = [
            1 => [
                $this->_industries[0], $this->_industries[1]
            ],
            2 => [
                $this->_industries[1]
            ],
            3 => [
                $this->_industries[0]
            ],
            4 => [
                $this->_industries[1], $this->_industries[2]
            ],
            5 => [
                $this->_industries[0], $this->_industries[1], $this->_industries[2]
            ]
        ];

        $this->_projectProfessions = [
            1 => [
                $this->_professions[0], $this->_professions[1], $this->_professions[2], $this->_professions[3]
            ],
            2 => [
                $this->_professions[1]
            ],
            3 => [
                $this->_professions[0], $this->_professions[3]
            ],
            4 => [
                $this->_professions[1], $this->_professions[2]
            ],
            5 => [
                $this->_professions[0], $this->_professions[2], $this->_professions[3]
            ]
        ];

        $this->_projectSkills = [
            1 => [
                $this->_skills[0], $this->_skills[1], $this->_skills[3],
            ],
            2 => [
                $this->_skills[3], $this->_skills[4]
            ],
            3 => [
                $this->_skills[0],
            ],
            4 => [

            ],
            5 => [
                $this->_skills[2], $this->_skills[3],
            ]
        ];

        parent::setUp();
    }

    protected function setMatchedProjectIdsFromSearch(Project_Search_Complex $search)
    {
        $this->_matchedProjects = [];
        $projects = $search->getMatchedProjects();

        foreach ($projects as $project) {
            $this->_matchedProjects[] = $project->project_id;
        }
    }

    protected function getSearch($relationName, $relationValue, $projects = null)
    {
        $data = [
            $relationName => $relationValue,
            'complex' => true
        ];

        $search = Project_Search_Factory::makeSearch($data);

        if (!$projects) {
            $projects = $this->_projects;
        }

        $search->setProjects($projects);

        return $search;
    }

    // -------- Iparagak --------

    /**
     * @group industry
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryOk()
    {
        $projectIndustryMock = $this->getMockAny('\Model_Project_Industry', 'getAll', $this->_projectIndustries);

        $industries = [1];
        $search = $this->getSearch('industries', $industries);
        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 3, 5], $this->_matchedProjects);
        $this->assertArrayNotSubset([2, 4], $this->_matchedProjects);

        $industries = [1, 3];
        $search = $this->getSearch('industries', $industries);
        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 3, 4, 5], $this->_matchedProjects);
        $this->assertArrayNotSubset([2], $this->_matchedProjects);

        $industries = [1, 3, 2];
        $search = $this->getSearch('industries', $industries);
        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 2, 3, 4, 5], $this->_matchedProjects);
    }

    /**
     * @group industry
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNotOk()
    {
        $projectIndustryMock = $this->getMockAny('\Model_Project_Industry', 'getAll', $this->_projectIndustries);

        $industries = [4];
        $search = $this->getSearch('industries', $industries);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertEmpty($this->_matchedProjects);

        $industries = [4, 11];
        $search = $this->getSearch('industries', $industries);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertEmpty($this->_matchedProjects);
    }

    /**
     * @group industry
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNoPostOk()
    {
        $projectIndustryMock = $this->getMockAny('\Model_Project_Industry', 'getAll', $this->_projectIndustries);

        $industries = [];
        $search = $this->getSearch('industries', $industries);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectIndustryMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 2, 3, 4, 5], $this->_matchedProjects);
    }

    // --------- Szakteruletek --------

    /**
     * @group profession
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionOk()
    {
        $projectProfessionMock = $this->getMockAny('\Model_Project_Profession', 'getAll', $this->_projectProfessions);

        $professions = [1];
        $search = $this->getSearch('professions', $professions);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 3, 5], $this->_matchedProjects);
        $this->assertArrayNotSubset([2, 4], $this->_matchedProjects);

        $professions = [1, 3];
        $search = $this->getSearch('professions', $professions);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 3, 4, 5], $this->_matchedProjects);
        $this->assertArrayNotSubset([2], $this->_matchedProjects);

        $professions = [1, 3, 2];
        $search = $this->getSearch('professions', $professions);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertArraySubset([1, 2, 3, 4, 5], $this->_matchedProjects);
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionNotOk()
    {
        $projectProfessionMock  = $this->getMockBuilder('\Model_Project_Profession')->getMock();

        $projectProfessionMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectProfessions));

        $professions = [6];
        $search = Project_Search_Factory::makeSearch(['professions' => $professions, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $professions = [7, 10];
        $search = Project_Search_Factory::makeSearch(['professions' => $professions, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionNoPostOk()
    {
        $projectProfessionMock  = $this->getMockBuilder('\Model_Project_Industry')->getMock();

        $projectProfessionMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectIndustries));

        $professions = [];
        $search = Project_Search_Factory::makeSearch(['professions' => $professions, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectProfessionMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertTrue(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertTrue(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));
    }

    // ------- Kepessegek ---------

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillOrOk()
    {
        $projectSkillMock  = $this->getMockBuilder('\Model_Project_Skill')->getMock();

        $projectSkillMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectSkills));

        $skills = [1];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 1]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $skills = [1, 3];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));

        $skills = [1, 3, 2];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillOrNotOk()
    {
        $projectSkillMock  = $this->getMockBuilder('\Model_Project_Skill')->getMock();

        $projectSkillMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectSkills));

        $skills = [12];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 1]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $skills = [14, 32, 33, 65, 101];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndOk()
    {
        $projectSkillMock  = $this->getMockBuilder('\Model_Project_Skill')->getMock();

        $projectSkillMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectSkills));

        $skills = [1];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $skills = [1, 3];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $skills = [4, 5];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertTrue(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndNotOk()
    {
        $projectSkillMock  = $this->getMockBuilder('\Model_Project_Skill')->getMock();

        $projectSkillMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectSkills));

        $skills = [1, 2, 3, 4];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));

        $skills = [98];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertFalse(in_array(1, $this->_matchedProjects));
        $this->assertFalse(in_array(2, $this->_matchedProjects));
        $this->assertFalse(in_array(3, $this->_matchedProjects));
        $this->assertFalse(in_array(4, $this->_matchedProjects));
        $this->assertFalse(in_array(5, $this->_matchedProjects));
    }

    /**
     * @covers Project_Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndNoPostOk()
    {
        $projectSkillMock  = $this->getMockBuilder('\Model_Project_Skill')->getMock();

        $projectSkillMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_projectSkills));

        $skills = [];
        $search = Project_Search_Factory::makeSearch(['skills' => $skills, 'complex' => true, 'skill_relation' => 2]);
        $search->setProjects($this->_projects);

        $this->invokeMethod($search, 'searchRelationsInProjects', [$projectSkillMock]);
        $this->setMatchedProjectIdsFromSearch($search);

        $this->assertTrue(in_array(1, $this->_matchedProjects));
        $this->assertTrue(in_array(2, $this->_matchedProjects));
        $this->assertTrue(in_array(3, $this->_matchedProjects));
        $this->assertTrue(in_array(4, $this->_matchedProjects));
        $this->assertTrue(in_array(5, $this->_matchedProjects));
    }

    public function assertPostConditions()
    {
        // Minden teszt utan ellenorzi, hogy a talalti lista egyedi projekteket tartalmaz
        $unique = array_unique($this->_matchedProjects);
        $this->assertEquals($unique, $this->_matchedProjects);
    }
}