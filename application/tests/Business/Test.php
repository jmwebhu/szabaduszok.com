<?php

class Business_Test extends Unittest_TestCase
{
    private $_projectsSingle    = [];
    private $_projectsMulti     = [];
    private $_projectsMultiWrong = [];
    private $_projects          = [];

    /**
     * @covers Business::getIdsFromModelsSingle()
     */
    public function testGetIdsFromModelsSingleOk()
    {
        $ids = Business::getIdsFromModelsSingle($this->_projectsSingle, 'project_id');
        $this->assertArraySubset([1, 2, 3, 4, 5], $ids);
    }

    /**
     * @covers Business::getIdsFromModelsSingle()
     */
    public function testGetIdsFromModelsSingleEmpty()
    {
        $ids = Business::getIdsFromModelsSingle([], 'project_id');
        $this->assertEmpty($ids);
    }

    /**
     * @covers Business::getIdsFromModelsSingle()
     */
    public function testGetIdsFromModelsSingleWrongType()
    {
        $models = $this->_projectsSingle;
        $models[] = new Date();

        $ids = Business::getIdsFromModelsSingle($models, 'project_id');
        $this->assertEmpty($ids);
    }

    /**
     * @covers Business::getIdsFromModelsSingle()
     */
    public function testGetIdsFromModelsSingleWrongPrimaryKey()
    {
        $ids = Business::getIdsFromModelsSingle($this->_projectsSingle, 'projectxy_id');
        $this->assertEmpty($ids);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiOk()
    {
        $ids = Business::getIdsFromModelsMulti($this->_projectsMulti, 'project_id');
        $this->assertArraySubset([11 => [1, 2, 3, 4, 5], 12 => [14]], $ids);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiEmpty()
    {
        $ids = Business::getIdsFromModelsMulti([], 'project_id');
        $this->assertEmpty($ids);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiWrongType()
    {
        $models = $this->_projectsMulti;
        $models[21] = [];
        $models[21][] = new Date();

        $ids = Business::getIdsFromModelsMulti($models, 'project_id');
        $this->assertEmpty($ids[21]);
        $this->assertNotEmpty($ids[11]);
        $this->assertNotEmpty($ids[12]);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiWrongPrimaryKey()
    {
        $ids = Business::getIdsFromModelsMulti($this->_projectsMulti, 'projectxy_id');
        $this->assertEmpty($ids[11]);
        $this->assertEmpty($ids[12]);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiWrongParams()
    {
        $ids = Business::getIdsFromModelsMulti($this->_projectsMultiWrong, 'project_id');

        $this->assertNotEmpty($ids[11]);
        $this->assertNotEmpty($ids[12]);
        $this->assertEmpty($ids[13]);
        $this->assertEmpty($ids[14]);
    }

    /**
     * @covers Business::checkModel()
     */
    public function testCheckModelOk()
    {
        $orm = new Model_Project();
        $business = new Business($orm);

        $this->assertTrue($this->invokeMethod($business, 'checkModel', [$orm]));
    }

    /**
     * @covers Business::checkModel()
     * @expectedException Exception
     */
    public function testCheckModelNotOk()
    {
        $orm = new Model_Project();
        $notOrm = new StringBuilder();
        $business = new Business($orm);

        $this->assertTrue($this->invokeMethod($business, 'checkModel', [$notOrm]));
    }

    /**
     * @covers Business::checkPrimaryKey()
     */
    public function testCheckPrimaryKeyOk()
    {
        $orm = new Model_Project();
        $business = new Business($orm);

        $this->assertTrue($this->invokeMethod($business, 'checkPrimaryKey', [$orm, 'project_id']));
    }

    /**
     * @covers Business::checkPrimaryKey()
     * @expectedException Exception
     */
    public function testCheckPrimaryKeyNotOk()
    {
        $orm = new Model_Project();
        $business = new Business($orm);

        $this->invokeMethod($business, 'checkPrimaryKey', [$orm, 'user_id']);
    }

    /**
     * @covers Business::getIdFromModel()
     */
    public function testGetIdFromModelWithPrimaryKeyOk()
    {
        $orm = new Model_Project();
        $orm->project_id = 1;
        $business = new Business($orm);

        $id = $this->invokeMethod($business, 'getIdFromModel', [$orm, 'project_id']);
        $this->assertEquals(1, $id);
    }

    /**
     * @covers Business::getIdFromModel()
     * @expectedException Exception
     */
    public function testGetIdFromModelWithPrimaryKeyNotOk()
    {
        $orm = new Model_Project();
        $orm->project_id = 1;
        $business = new Business($orm);

        $this->invokeMethod($business, 'getIdFromModel', [$orm, 'adsf_id']);
    }

    /**
     * @covers Business::getIdFromModel()
     */
    public function testGetIdFromModelWithoutPrimaryKeyOk()
    {
        $orm = new Model_Project();
        $orm->project_id = 1;
        $business = new Business($orm);

        $id = $this->invokeMethod($business, 'getIdFromModel', [$orm]);
        $this->assertEquals(1, $id);
    }

    /**
     * @covers Business::getIdFromModel()
     */
    public function testGetIdFromModelWithoutPrimaryKeyNotOk()
    {
        $orm = new Model_Project_Skill();
        $business = new Business($orm);

        $id = $this->invokeMethod($business, 'getIdFromModel', [$orm]);
        $this->assertEmpty($id);
    }

    public function setUp()
    {
        $this->setUpProjects(5);
        $this->setUpProjectsSingle(5);
        $this->setUpProjectsMulti();
        $this->setUpProjectsMultiWrong();

        parent::setUp();
    }

    protected function setUpProjects($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $project = new Model_Project();
            $project->project_id = $i;

            $this->_projects[] = $project;
        }
    }

    protected function setUpProjectsSingle($max)
    {
        if (empty($this->_projects)) {
            $this->setUpProjects($max);
        }

        $this->_projectsSingle = $this->_projects;
    }

    protected function setUpProjectsMulti()
    {
        $project = new Model_Project();
        $project->project_id = 14;

        $this->_projectsMulti = [
            11 => $this->_projectsSingle,
            12 => [
                $project
            ]
        ];
    }

    protected function setUpProjectsMultiWrong()
    {
        $project = new Model_Project();
        $project->project_id = 14;

        $this->_projectsMultiWrong = [
            11 => $this->_projectsSingle,
            12 => [
                $project
            ],
            13 => 'asdf',
            14 => 1
        ];
    }
}