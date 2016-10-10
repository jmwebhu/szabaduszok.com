<?php

class BusinessTest extends Unittest_TestCase
{
    private $_projectsSingle    = [];
    private $_projectsMulti     = [];
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
        $models[21][] = new Date();

        $ids = Business::getIdsFromModelsSingle($models, 'project_id');
        $this->assertEmpty($ids);
    }

    /**
     * @covers Business::getIdsFromModelsMulti()
     */
    public function testGetIdsFromModelsMultiWrongPrimaryKey()
    {
        $ids = Business::getIdsFromModelsSingle($this->_projectsMulti, 'projectxy_id');
        $this->assertEmpty($ids);
    }

    public function setUp()
    {
        $this->setUpProjects(5);
        $this->setUpProjectsSingle(5);
        $this->setUpProjectsMulti();

        parent::setUp();
    }

    protected function setUpProjects($max)
    {
        for ($i = 1; $i <= $max; $i++) {
            $project = new ORM();
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
        $project = new ORM();
        $project->project_id = 14;

        $this->_projectsMulti = [
            11 => $this->_projectsSingle,
            12 => [
                $project
            ]
        ];

    }
}