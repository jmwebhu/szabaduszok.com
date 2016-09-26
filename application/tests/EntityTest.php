<?php

class EntityTest extends Unittest_TestCase
{
    /**
     * @covers Entity::mapModelToThis()
     */
    public function testMapModelToThis()
    {
        $projectModel = new Model_Project();
        $projectModel->project_id = 1;
        $projectModel->name = 'Teszt';
        $projectModel->short_description = 'Rövid leírás';
        $projectModel->long_description = 'Hosszú leírás';
        $projectModel->email = 'joomartin@jmweb.hu';
        $projectModel->phonenumber = '06301923380';
        $projectModel->search_text = 'Teszt kereső szöveg';
        $projectModel->expiration_date = '2016-12-12';
        $projectModel->salary_type = 1;
        $projectModel->salary_low = 2000;
        $projectModel->salary_high = 2500;
        $projectModel->slug = 'teszt';

        $project = new Entity_Project();
        $project->setModel($projectModel);

        $this->assertEquals(1, $project->getProjectId());
        $this->assertEquals('Teszt', $project->getName());
        $this->assertEquals('Rövid leírás', $project->getShortDescription());
        $this->assertEquals('Hosszú leírás', $project->getLongDescription());
        $this->assertEquals('joomartin@jmweb.hu', $project->getEmail());
        $this->assertEquals('Teszt kereső szöveg', $project->getSearchText());
        $this->assertEquals('2016-12-12', $project->getExpirationDate());
        $this->assertEquals(1, $project->getSalaryType());
        $this->assertEquals(2000, $project->getSalaryLow());
        $this->assertEquals(2500, $project->getSalaryHigh());
        $this->assertEquals('teszt', $project->getSlug());
        $this->assertNull($project->getIsActive());
        $this->assertNull($project->getIsPaid());
    }

    /**
     * @covers Entity::mapThisToModel()
     */
    public function testMapThisToModel()
    {
        $project = new Entity_Project();
        $project->setProjectId(1);
        $project->setName('Teszt');
        $project->setShortDescription('Rövid leírás');
        $project->setLongDescription('Hosszú leírás');
        $project->setEmail('joomartin@jmweb.hu');
        $project->setPhonenumber('06301923380');
        $project->setSearchText('Teszt kereső szöveg');
        $project->setExpirationDate('2016-12-12');
        $project->setSalaryType(1);
        $project->setSalaryLow(2000);
        $project->setSalaryHigh(2500);
        $project->setSlug('teszt');

        $this->invokeMethod($project, 'mapThisToModel');

        $model = $project->getModel();

        $this->assertEquals(1, $model->project_id);
        $this->assertEquals('Teszt', $model->name);
        $this->assertEquals('Rövid leírás', $model->short_description);
        $this->assertEquals('Hosszú leírás', $model->long_description);
        $this->assertEquals('06301923380', $model->phonenumber);
        $this->assertEquals('Teszt kereső szöveg', $model->search_text);
        $this->assertEquals('2016-12-12', $model->expiration_date);
        $this->assertEquals(1, $model->salary_type);
        $this->assertEquals(2000, $model->salary_low);
        $this->assertEquals(2500, $model->salary_high);
        $this->assertEquals('teszt', $model->slug);
        $this->assertNull($model->is_active);
        $this->assertNull($model->is_paid);
    }

    /**
     * @covers Entity::getStdObject
     */
    public function testGetStdObject()
    {
        $project = new Entity_Project();
        $project->setProjectId(1);
        $project->setName('Teszt');
        $project->setShortDescription('Rövid leírás');
        $project->setLongDescription('Hosszú leírás');
        $project->setEmail('joomartin@jmweb.hu');
        $project->setPhonenumber('06301923380');
        $project->setSearchText('Teszt kereső szöveg');
        $project->setExpirationDate('2016-12-12');
        $project->setSalaryType(1);
        $project->setSalaryLow(2000);
        $project->setSalaryHigh(2500);
        $project->setSlug('teszt');

        $obj = $this->invokeMethod($project, 'getStdObject');

        $this->assertEquals(1, $obj->project_id);
        $this->assertEquals('Teszt', $obj->name);
        $this->assertEquals('Rövid leírás', $obj->short_description);
        $this->assertEquals('Hosszú leírás', $obj->long_description);
        $this->assertEquals('joomartin@jmweb.hu', $obj->email);
        $this->assertEquals('06301923380', $obj->phonenumber);
        $this->assertEquals('Teszt kereső szöveg', $obj->search_text);
        $this->assertEquals('2016-12-12', $obj->expiration_date);
        $this->assertEquals(1, $obj->salary_type);
        $this->assertEquals(2000, $obj->salary_low);
        $this->assertEquals(2500, $obj->salary_high);
        $this->assertEquals('teszt', $obj->slug);
        $this->assertNull($obj->is_active);
        $this->assertNull($obj->is_paid);
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
}