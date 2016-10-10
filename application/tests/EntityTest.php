<?php

class EntityTest extends Unittest_TestCase
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