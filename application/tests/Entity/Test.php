<?php

class Entity_Test extends Unittest_TestCase
{
    /**
     * @var Entity_Project
     */
    private $_entity;

    /**
     * @var ORM
     */
    private $_model;

    /**
     * @covers Entity::setModel()
     */
    public function testSetModel()
    {
        $this->givenTestModelWithId(1);
        $this->_entity->setModel($this->_model);
        $this->thenEntityShouldEqualsTo();
    }

    /**
     * @covers Entity::mapThisToModel()
     */
    public function testMapThisToModel()
    {
        $this->givenTestEntityWithId(1);

        $this->invokeMethod($this->_entity, 'mapThisToModel');
        $this->_model = $this->_entity->getModel();

        $this->thenEntityShouldEqualsTo();
    }

    /**
     * @covers Entity::mapModelToThis()
     */
    public function testMapModelToThis()
    {
        $this->givenTestModelWithId(1);
        $entity = new Entity_Project();

        $this->invokeMethod($entity, 'mapModelToThis');
        $this->_model = $this->_entity->getModel();

        $this->thenEntityShouldEqualsTo();
    }

    /**
     * @covers Entity::map()
     */
    public function testMapOk()
    {
        $this->givenTestEntityWithId(1);
        $project = new Model_Project();

        $this->invokeMethod($this->_entity, 'setDestinationObject', [$project]);
        $this->invokeMethod($this->_entity, 'setTargetObject', [$this->_entity]);

        $result = $this->invokeMethod($this->_entity, 'map');
        $this->_model = $this->_entity->getModel();

        $this->thenEntityShouldEqualsTo();
        $this->assertTrue($result);
    }

    /**
     * @covers Entity::map()
     */
    public function testMapNotOk()
    {
        $this->givenTestEntityWithId(1);

        $this->invokeMethod($this->_entity, 'setDestinationObject', ['adf']);
        $this->invokeMethod($this->_entity, 'setTargetObject', [$this->_entity]);

        $result = $this->invokeMethod($this->_entity, 'map');

        $this->assertFalse($result);
    }

    /**
     * @covers Entity::mapThisToStdObject
     */
    public function testMapThisToStdObject()
    {
        $this->givenTestEntityWithId(1);

        $stdObject = $this->invokeMethod($this->_entity, 'mapThisToStdObject');
        $this->thenEntityShouldEqualsTo($stdObject);
    }

    /**
     * @covers Entity::getUnprefixedPropertyName
     */
    public function testGetUnprefixedPropertyName()
    {
        $project = new Entity_Project();

        $id = $this->invokeMethod($project, 'getUnprefixedPropertyName', ['_project_id']);
        $name = $this->invokeMethod($project, 'getUnprefixedPropertyName', ['name']);

        $this->assertEquals('project_id', $id);
        $this->assertEquals('name', $name);
    }

    /**
     * @covers Entity::getEntityName()
     */
    public function testGetEntityName()
    {
        $entity                 = new Entity_Project();
        $classMockStandard      = 'Mock_Entity_Project_d2f09e';
        $classStandard          = 'Entity_Project';
        $classMoreSyllables     = 'Entity_User_Freelancer';
        $classMockMoreSyllables = 'Mock_Entity_User_Employer_e90a13';
        $classOwnMock           = 'Entity_ProjectMock';

        $nameMockStandard       = $this->invokeMethod($entity, 'getEntityName', [$classMockStandard]);
        $nameStandard           = $this->invokeMethod($entity, 'getEntityName', [$classStandard]);
        $nameMoreSyllables      = $this->invokeMethod($entity, 'getEntityName', [$classMoreSyllables]);
        $nameMockMoreSyllables  = $this->invokeMethod($entity, 'getEntityName', [$classMockMoreSyllables]);
        $nameDefault            = $this->invokeMethod($entity, 'getEntityName', []);
        $nameOwnMock            = $this->invokeMethod($entity, 'getEntityName', [$classOwnMock]);

        $this->assertEquals('Project', $nameMockStandard);
        $this->assertEquals('Project', $nameStandard);
        $this->assertEquals('User_Freelancer', $nameMoreSyllables);
        $this->assertEquals('User_Employer', $nameMockMoreSyllables);
        $this->assertEquals('Project', $nameDefault);
        $this->assertEquals('ProjectMock', $nameOwnMock);
    }

    /**
     * @covers Entity::getEntitiesFromModels(
     */
    public function testGetEntitiesFromModels()
    {
        $project1 = new Model_Project();
        $project1->project_id = 1;

        $project2 = new Model_Project();
        $project2->project_id = 2;

        $projects = [
            $project1, $project2
        ];

        $entity = new Entity_Project();
        $entities = $entity->getEntitiesFromModels($projects);

        $this->assertEquals(1, $entities[0]->getProjectId());
        $this->assertEquals(2, $entities[1]->getProjectId());
    }

    /**
     * @covers Entity::validateObjects()
     */
    public function testValidateObjectsOk()
    {
        $entity = new Entity_Project();
        $this->invokeMethod($entity, 'setDestinationObject', [new Model_Project()]);
        $this->invokeMethod($entity, 'setTargetObject', [new Entity_Project()]);

        $valid = $this->invokeMethod($entity, 'validateObjects', []);
        $this->assertTrue($valid);
    }

    /**
     * @covers Entity::validateObjects()
     * @expectedException Exception
     */
    public function testValidateObjectsNotOk()
    {
        $entity = new Entity_Project();
        $this->invokeMethod($entity, 'setDestinationObject', [new Model_Project()]);
        $this->invokeMethod($entity, 'setTargetObject', [12]);

        $this->invokeMethod($entity, 'validateObjects', []);
    }

    /**
     * @covers Entity::setStdObjectUnprefixedProperty()
     */
    public function testSetStdObjectUnprefixedPropertyOk()
    {
        $entity = new Entity_Project();
        $this->invokeMethod($entity, 'setStdObjectUnprefixedProperty', ['_project_id', '1']);
        $this->invokeMethod($entity, 'setStdObjectUnprefixedProperty', ['name', 'teszt']);

        $stdObject = $this->invokeMethod($entity, 'getStdObject', []);

        $this->assertEquals('1', $stdObject->project_id);
        $this->assertEquals('teszt', $stdObject->name);
    }

    /**
     * @covers Entity::mapOnePropertyToStdObject()
     */
    public function testMapOnePropertyToStdObject()
    {
        $entity = new Entity_Project();
        $this->invokeMethod($entity, 'mapOnePropertyToStdObject', ['_project_id', '1']);
        $this->invokeMethod($entity, 'mapOnePropertyToStdObject', ['short_description', 'rövid leírás']);
        $this->invokeMethod($entity, 'mapOnePropertyToStdObject', ['_search', 'search']);

        $stdObject = $this->invokeMethod($entity, 'getStdObject', []);

        $this->assertEquals('1', $stdObject->project_id);
        $this->assertEquals('rövid leírás', $stdObject->short_description);
        $this->assertFalse(property_exists($stdObject, 'search'));
        $this->assertFalse(property_exists($stdObject, '_search'));
    }

    /**
     * @covers Entity::setPrimaryKeyFromModel()
     */
    public function testSetPrimaryKeyFromModel()
    {
        $model = new Model_Project();
        $model->project_id = 1;

        $entity = new Entity_Project($model);
        $this->invokeMethod($entity, 'setPrimaryKeyFromModel', []);

        $this->assertEquals(1, $entity->getProjectId());
    }

    /**
     * @covers Entity::mapProperties()
     */
    public function testMapPropertiesFromModelToEntity()
    {
        $this->givenTestModelWithId(10);
        $this->_entity = new Entity_Project();

        $this->invokeMethod($this->_entity, 'setDestinationObject', [$this->_model]);
        $this->invokeMethod($this->_entity, 'setTargetObject', [$this->_entity]);

        $this->invokeMethod($this->_entity, 'mapProperties', []);

        $this->thenEntityShouldEqualsTo($this->_model);
    }

    /**
     * @covers Entity::mapProperties()
     */
    public function testMapPropertiesFromEntityToModel()
    {
        $this->givenTestEntityWithId(10);
        $this->_model = new Model_Project();

        $this->invokeMethod($this->_entity, 'setDestinationObject', [$this->_entity]);
        $this->invokeMethod($this->_entity, 'setTargetObject', [$this->_model]);

        $this->invokeMethod($this->_entity, 'mapProperties', []);

        $this->thenEntityShouldEqualsTo($this->_model);
    }

    /**
     * @covers Entity::__construct()
     */
    public function testConstructEmptyOk()
    {
        $entity = new Entity_Project();

        $this->invokeMethod($entity, '__construct', []);

        $this->assertTrue($entity->getModel() instanceof Model_Project);
        $this->assertTrue($entity->getBusiness() instanceof Business_Project);
        $this->assertEmpty($entity->getProjectId());
    }

    /**
     * @covers Entity::__construct()
     */
    public function testConstructModelOk()
    {
        $entity = new Entity_Project();
        $model = new Model_Project();
        $model->project_id = 1;

        $this->invokeMethod($entity, '__construct', [$model]);

        $this->assertTrue($entity->getModel() instanceof Model_Project);
        $this->assertTrue($entity->getBusiness() instanceof Business_Project);
        $this->assertEquals(1, $entity->getProjectId());
        $this->assertEquals($model, $entity->getModel());
    }

    /**
     * @covers Entity::__construct()
     */
    public function testConstructIdOk()
    {
        $entity = new Entity_Project();
        $project = new Model_Project(1);

        $this->invokeMethod($entity, '__construct', [1]);

        $this->assertTrue($entity->getModel() instanceof Model_Project);
        $this->assertTrue($entity->getBusiness() instanceof Business_Project);
        $this->assertEquals($project->project_id, $entity->getProjectId());
    }

    protected function givenTestEntityWithId($id)
    {
        $entity = new Entity_Project();
        $entity->setProjectId($id);
        $entity->setName('Teszt');
        $entity->setShortDescription('Rövid leírás');
        $entity->setLongDescription('Hosszú leírás');
        $entity->setEmail('joomartin@jmweb.hu');
        $entity->setPhonenumber('06301923380');
        $entity->setSearchText('Teszt kereső szöveg');
        $entity->setExpirationDate('2016-12-12');
        $entity->setSalaryType(1);
        $entity->setSalaryLow(2000);
        $entity->setSalaryHigh(2500);
        $entity->setSlug('teszt');

        $this->_entity = $entity;
    }

    protected function givenTestModelWithId($id)
    {
        $model = new Model_Project();
        $model->project_id = $id;
        $model->name = 'Teszt';
        $model->short_description = 'Rövid leírás';
        $model->long_description = 'Hosszú leírás';
        $model->email = 'joomartin@jmweb.hu';
        $model->phonenumber = '06301923380';
        $model->search_text = 'Teszt kereső szöveg';
        $model->expiration_date = '2016-12-12';
        $model->salary_type = 1;
        $model->salary_low = 2000;
        $model->salary_high = 2500;
        $model->slug = 'teszt';

        $this->_model = $model;
    }

    protected function thenEntityShouldEqualsTo($object = null)
    {
        if (!$object) {
            $object = $this->_model;
        }

        $this->assertEquals($object->project_id, $this->_entity->getProjectId());
        $this->assertEquals($object->name, $this->_entity->getName());
        $this->assertEquals($object->short_description, $this->_entity->getShortDescription());
        $this->assertEquals($object->long_description, $this->_entity->getLongDescription());
        $this->assertEquals($object->email, $this->_entity->getEmail());
        $this->assertEquals($object->search_text, $this->_entity->getSearchText());
        $this->assertEquals($object->expiration_date, $this->_entity->getExpirationDate());
        $this->assertEquals($object->salary_type, $this->_entity->getSalaryType());
        $this->assertEquals($object->salary_low, $this->_entity->getSalaryLow());
        $this->assertEquals($object->salary_high, $this->_entity->getSalaryHigh());
        $this->assertEquals($object->slug, $this->_entity->getSlug());

        $this->assertNull($this->_entity->getIsActive());
        $this->assertNull($this->_entity->getIsPaid());
    }

    public function setUp()
    {
        $this->_entity = new Entity_Project();
        $this->_model = new Model_Project();
        parent::setUp();
    }
}