<?php

class Model_Project_Test extends Unittest_TestCase
{
    private $_projects              = [];

    private $_industries            = [];
    private $_professions           = [];
    private $_skills                = [];

    private $_projectIndustries     = [];
    private $_projectProfessions    = [];
    private $_projectSkills         = [];

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationIndustriesOk()
    {
        $this->setMockAny('\Model_Project_Industry', 'getAll', $this->_projectIndustries);
        $project = $this->_projects[0];

        $industries = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertSameRelations($this->_projectIndustries[1], $industries);
    }

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationIndustriesEmpty()
    {
        $this->setMockAny('\Model_Project_Industry', 'getAll', []);
        $project = $this->_projects[0];

        $industries = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertEmpty($industries);
    }

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationProfessionsOk()
    {
        $this->setMockAny('\Model_Project_Profession', 'getAll', $this->_projectProfessions);
        $project = $this->_projects[1];

        $professions = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertSameRelations($this->_projectProfessions[2], $professions);
    }

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationProfessionsEmpty()
    {
        $this->setMockAny('\Model_Project_Profession', 'getAll', []);
        $project = $this->_projects[1];

        $industries = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertEmpty($industries);
    }

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationSkillsOk()
    {
        $this->setMockAny('\Model_Project_Skill', 'getAll', $this->_projectSkills);
        $project = $this->_projects[0];

        $skills = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertSameRelations($this->_projectSkills[1], $skills);
    }

    /**
     * @covers ORM::getRelation()
     */
    public function testGetRelationSkillsEmpty()
    {
        $this->setMockAny('\Model_Project_Skill', 'getAll', []);
        $project = $this->_projects[1];

        $skills = $this->invokeMethod($project, 'getRelation', [$this->_mock]);

        $this->assertEmpty($skills);
    }

    protected function assertSameRelations($expected, $actual)
    {
        foreach ($expected as $i => $industry) {
            $this->assertEquals($industry, $actual[$i]);
        }
    }

    public function setUp()
    {
        $this->setUpProjects(5);
        $this->setUpIndustries(3);
        $this->setUpProfessions(4);
        $this->setUpSkills(5);

        $this->setUpRelations();
    }

    protected function setUpProjects($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $project = new Model_Project();
            $project->project_id = $i;

            $this->_projects[] = $project;
        }
    }

    protected function setUpIndustries($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $industry = new Model_Industry();
            $industry->industry_id = $i;

            $this->_industries[] = $industry;
        }
    }

    protected function setUpProfessions($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $profession = new Model_Profession();
            $profession->profession_id = $i;

            $this->_professions[] = $profession;
        }
    }

    protected function setUpSkills($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $skill = new Model_Skill();
            $skill->skill_id = $i;

            $this->_skills[] = $skill;
        }
    }

    protected function setUpRelations()
    {
        $this->setUpProjectIndustries();
        $this->setUpProjectProfessions();
        $this->setUpProjectSkills();
    }

    protected function setUpProjectIndustries()
    {
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
    }

    protected function setUpProjectProfessions()
    {
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
    }

    protected function setUpProjectSkills()
    {
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
    }
}