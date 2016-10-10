<?php

class ProjectSearchComplexTest extends Unittest_TestCase
{
    const TYPE_INDUSTRY     = 1;
    const TYPE_PROFESSION   = 2;
    const TYPE_SKILLS       = 3;

    private $_projects              = [];
    private $_matchedProjects       = [];

    private $_industries            = [];
    private $_professions           = [];
    private $_skills                = [];

    private $_projectIndustries     = [];
    private $_projectProfessions    = [];
    private $_projectSkills         = [];

    private $_searchedIndustries    = [];
    private $_searchedProfessions   = [];
    private $_searchedSkills        = [];

    private $_searchType;

    private $_skillRelation         = 1;

    // -------- Iparagak --------

    /**
     * @group industry
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryOk()
    {
        $this->givenIndustries([1]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 5]);
        $this->thenMatchesShouldNotContain([2, 4]);

        $this->givenIndustries([1, 3]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 4, 5]);
        $this->thenMatchesShouldNotContain([2]);

        $this->givenIndustries([1, 3, 2]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 2, 3, 4, 5]);
    }

    /**
     * @group industry
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNotOk()
    {
        $this->givenIndustries([4]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();

        $this->givenIndustries([4, 11]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();
    }

    /**
     * @group industry
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsIndustryNoPostOk()
    {
        $this->givenIndustries([]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 2, 3, 4, 5]);
    }

    // --------- Szakteruletek --------

    /**
     * @group profession
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionOk()
    {
        $this->givenProfessions([1]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 5]);
        $this->thenMatchesShouldNotContain([2, 4]);

        $this->givenProfessions([1, 3]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 4, 5]);
        $this->thenMatchesShouldNotContain([2]);

        $this->givenProfessions([1, 3, 2]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 2, 3, 4, 5]);
        $this->thenMatchesShouldNotContain([]);
    }

    /**
     * @group profession
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionNotOk()
    {
        $this->givenProfessions([6]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();

        $this->givenProfessions([7, 10]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();
    }

    /**
     * @group profession
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsProfessionNoPostOk()
    {
        $this->givenProfessions([]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 2, 3, 4, 5]);
    }

    // ------- Kepessegek ---------

    /**
     * @group skill
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillOrOk()
    {
        $this->orBefore();

        $this->givenSkills([1]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3]);
        $this->thenMatchesShouldNotContain([2, 4, 5]);

        $this->givenSkills([1, 3]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 5]);
        $this->thenMatchesShouldNotContain([2, 4]);

        $this->givenSkills([1, 3, 2]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3, 5]);
        $this->thenMatchesShouldNotContain([2, 4]);
    }

    /**
     * @group skill
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillOrNotOk()
    {
        $this->orBefore();

        $this->givenSkills([12]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();

        $this->givenSkills([14, 32, 33, 65, 101]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();
    }

    /**
     * @group skill
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndOk()
    {
        $this->andBefore();

        $this->givenSkills([1]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 3]);
        $this->thenMatchesShouldNotContain([2, 4, 5]);

        $this->givenSkills([1, 3]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();

        $this->givenSkills([4, 5]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([2]);
        $this->thenMatchesShouldNotContain([1, 3, 4, 5]);
    }

    /**
     * @group skill
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndNotOk()
    {
        $this->andBefore();

        $this->givenSkills([1, 2, 3, 4]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();

        $this->givenSkills([98]);
        $this->whenSearch();

        $this->thenMatchesShouldEmpty();
    }

    /**
     * @group skill
     * @covers _Search_Complex::searchRelationsInProjects()
     */
    public function testSearchRelationsInProjectsSkillAndNoPostOk()
    {
        $this->andBefore();

        $this->givenSkills([]);
        $this->whenSearch();

        $this->thenMatchesShouldContain([1, 2, 3, 4, 5]);
    }

    public function assertPostConditions()
    {
        // Minden teszt utan ellenorzi, hogy a talalti lista egyedi projekteket tartalmaz
        $unique = array_unique($this->_matchedProjects);
        $this->assertEquals($unique, $this->_matchedProjects);
    }

    protected function givenIndustries(array $industries)
    {
        $this->setMockAny('\Model_Project_Industry', 'getAll', $this->_projectIndustries);
        $this->setMockAny('\Model_Project_Industry', 'getEndRelationModel', new Model_Industry());
        $this->setMockAny('\Model_Project_Industry', 'getSearchedRelationIdsPropertyName', '_searchedIndustryIds');

        $this->_searchedIndustries  = $industries;
        $this->_searchType          = self::TYPE_INDUSTRY;
    }

    protected function givenProfessions(array $professions)
    {
        $this->setMockAny('\Model_Project_Profession', 'getAll', $this->_projectProfessions);
        $this->setMockAny('\Model_Project_Profession', 'getEndRelationModel', new Model_Profession());
        $this->setMockAny('\Model_Project_Profession', 'getSearchedRelationIdsPropertyName', '_searchedProfessionIds');

        $this->_searchedProfessions = $professions;
        $this->_searchType          = self::TYPE_PROFESSION;
    }

    protected function givenSkills(array $skills)
    {
        $this->setMockAny('\Model_Project_Skill', 'getAll', $this->_projectSkills);
        $this->setMockAny('\Model_Project_Skill', 'getEndRelationModel', new Model_Skill());
        $this->setMockAny('\Model_Project_Skill', 'getSearchedRelationIdsPropertyName', '_searchedSkillIds');

        $this->_searchedSkills      = $skills;
        $this->_searchType          = self::TYPE_SKILLS;
    }

    protected function whenSearch()
    {
        switch ($this->_searchType) {
            case self::TYPE_INDUSTRY:
                $this->setSearch('Model_Project_Industry');
                break;

            case self::TYPE_PROFESSION:
                $this->setSearch('Model_Project_Profession');
                break;

            case self::TYPE_SKILLS:
                $this->setSearch('Model_Project_Skill');
                break;
        }

        $this->invokeMethod($this->_searchMock, 'search');
        $this->setMatchedProjectIdsFromSearch();
    }

    protected function thenMatchesShouldContain(array $array)
    {
        $this->assertArraySubset($array, $this->_matchedProjects);
    }

    protected function thenMatchesShouldNotContain(array $array)
    {
        $this->assertArrayNotSubset($array, $this->_matchedProjects);
    }

    protected function thenMatchesShouldEmpty()
    {
        $this->assertEmpty($this->_matchedProjects);
    }

    protected function andBefore()
    {
        $this->_skillRelation = Search_Relation_Skill::SKILL_RELATION_AND;
    }

    protected function orBefore()
    {
        $this->_skillRelation = Search_Relation_Skill::SKILL_RELATION_OR;
    }

    protected function setSearch($relationClassName)
    {
        $projectMock              = $this->getMockBuilder('\Model_Project')->getMock();

        $projectMock->expects($this->any())
            ->method('getOrderedByCreated')
            ->will($this->returnValue($this->_projects));

        $searchMock             = $this->getMockBuilder('\Search_Complex_Project')
            ->setConstructorArgs([
                $this->_searchedIndustries, $this->_searchedProfessions,
                $this->_searchedSkills, $this->_skillRelation])
            ->setMethods(['createSearchModel', 'getIndustryRelationModel', 'getProfessionRelationModel', 'getSkillRelationModel'])
            ->getMock();

        $searchMock->expects($this->any())
            ->method('createSearchModel')
            ->will($this->returnValue($projectMock));

        $relationMock = $this->getMockBuilder('\\' . $relationClassName)->setMethods(['getAll'])
            ->getMock();

        switch ($relationClassName) {
            case 'Model_Project_Industry':
                $return = $this->_projectIndustries;
                break;

            case 'Model_Project_Profession':
                $return = $this->_projectProfessions;
                break;

            case 'Model_Project_Skill':
                $return = $this->_projectSkills;
                break;
        }

        $relationMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($return));

        $searchMock->expects($this->any())
            ->method('getIndustryRelationModel')
            ->will($this->returnValue($relationMock));

        $searchMock->expects($this->any())
            ->method('getProfessionRelationModel')
            ->will($this->returnValue($relationMock));

        $searchMock->expects($this->any())
            ->method('getSkillRelationModel')
            ->will($this->returnValue($relationMock));

        $searchMock->setCurrentModel($projectMock);

        $this->_searchMock = $searchMock;
    }

    protected function setMatchedProjectIdsFromSearch()
    {
        $this->_matchedProjects     = [];
        $projects                   = $this->_searchMock->getMatchedModels();

        foreach ($projects as $project) {
            $this->_matchedProjects[] = $project->project_id;
        }
    }

    public function setUp()
    {
        $this->setUpEntities();
        $this->setUpRelations();

        parent::setUp();
    }

    protected function setUpEntities()
    {
        $this->setUpProjects(5);
        $this->setUpIndustries(3);
        $this->setUpProfessions(4);
        $this->setUpSkills(5);
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