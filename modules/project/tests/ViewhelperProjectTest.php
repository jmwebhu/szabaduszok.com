<?php

class ViewhelperProjectTest extends Unittest_TestCase
{       
    /**
     * @covers Viewhelper_Project::testGetSalary
     * @dataProvider testGetSalaryDataProvider
     */
    public function testGetSalary($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Viewhelper_Project::getPageTitle()
     */
    public function testGetPageTitle()
    {
        $create = Viewhelper_Project::getPageTitle('create');
        $edit = Viewhelper_Project::getPageTitle('edit');

        $this->assertEquals('Új Szabadúszó projekt', $create);
        $this->assertEquals('Szabadúszó projekt szerkesztése: ', $edit);
    }

    /**
     * @covers Viewhelper_Project::hasIdInput()
     */
    public function testHasIdInput()
    {
        $create = Viewhelper_Project::hasIdInput('create');
        $edit = Viewhelper_Project::hasIdInput('edit');

        $this->assertEquals(false, $create);
        $this->assertEquals(true, $edit);
    }

    /**
     * @covers Viewhelper_Project::getFormAction()
     */
    public function testGetFormAction()
    {
        $project = new Entity_Project();
        $project->setSlug('elso-projekt');

        $create = Viewhelper_Project::getFormAction('create');
        $edit = Viewhelper_Project::getFormAction('edit', $project);

        $this->assertEquals('http://127.0.0.1/szabaduszok.com/uj-szabaduszo-projekt', $create);
        $this->assertEquals('http://127.0.0.1/szabaduszok.com/szabaduszo-projekt-szerkesztes/elso-projekt', $edit);
    }

    /**
     * @covers Viewhelper_Project::getEmail()
     */
    public function testGetEmail()
    {
        $projectWithEmail = new Entity_Project();
        $projectWithEmail->setEmail('martin@szabaduszok.com');

        $projectWithoutEmail = new Entity_Project();

        $user = new Model_User();
        $user->email = 'joomartin@jmweb.hu';

        $create = Viewhelper_Project::getEmail($user, 'create', $projectWithEmail);
        $editWithEmail = Viewhelper_Project::getEmail($user, 'edit', $projectWithEmail);
        $editWithoutEmail = Viewhelper_Project::getEmail($user, 'edit', $projectWithoutEmail);

        $this->assertEquals('joomartin@jmweb.hu', $create);
        $this->assertEquals('martin@szabaduszok.com', $editWithEmail);
        $this->assertEquals('joomartin@jmweb.hu', $editWithoutEmail);
    }

    /**
     * @covers Viewhelper_Project::getPhonenumber()
     */
    public function testGetPhonenumber()
    {
        $projectWithPhonenumber = new Entity_Project();
        $projectWithPhonenumber->setPhonenumber('06301923380');

        $projectWithoutPhonenumber = new Entity_Project();

        $user = new Model_User();
        $user->phonenumber = '0694310320';

        $create = Viewhelper_Project::getPhonenumber($user, 'create', $projectWithPhonenumber);
        $editWithPhonenumber = Viewhelper_Project::getPhonenumber($user, 'edit', $projectWithPhonenumber);
        $editWithoutPhonenumber = Viewhelper_Project::getPhonenumber($user, 'edit', $projectWithoutPhonenumber);

        $this->assertEquals('0694310320', $create);
        $this->assertEquals('06301923380', $editWithPhonenumber);
        $this->assertEquals('0694310320', $editWithoutPhonenumber);
    }
    
    public function testGetSalaryDataProvider()
    {
        // Oraber, nincs felso
        $project1               = new Entity_Project();
        $project1->setSalaryType(1);
        $project1->setSalaryLow(1000);
        
        // Oraber, also != felso
        $project2               = new Entity_Project();
        $project2->setSalaryType(1);
        $project2->setSalaryLow(1000);
        $project2->setSalaryHigh(1500);
        
        // Fix osszeg, nincs felso
        $project3               = new Entity_Project();
        $project3->setSalaryType(2);
        $project3->setSalaryLow(100000);
        
        // Fix osszeg, also != felso
        $project4               = new Entity_Project();
        $project4->setSalaryType(2);
        $project4->setSalaryLow(100000);
        $project4->setSalaryHigh(150000);
        
        // Oraber, also == felso
        $project5               = new Entity_Project();
        $project5->setSalaryType(1);
        $project5->setSalaryLow(1000);
        $project5->setSalaryHigh(1000);
        
        // Fix osszeg, also == felso
        $project6               = new Entity_Project();
        $project6->setSalaryType(2);
        $project6->setSalaryLow(100000);
        $project6->setSalaryHigh(100000);
        
        return [
            ['1 000 Ft /óra', Viewhelper_Project::getSalary($project1)['salary'] . Viewhelper_Project::getSalary($project1)['postfix']],
            ['1 000 - 1 500 Ft /óra', Viewhelper_Project::getSalary($project2)['salary'] . Viewhelper_Project::getSalary($project2)['postfix']],
            ['100 000 Ft', Viewhelper_Project::getSalary($project3)['salary'] . Viewhelper_Project::getSalary($project3)['postfix']],
            ['100 000 - 150 000 Ft', Viewhelper_Project::getSalary($project4)['salary'] . Viewhelper_Project::getSalary($project4)['postfix']],
            ['1 000 Ft /óra', Viewhelper_Project::getSalary($project5)['salary'] . Viewhelper_Project::getSalary($project5)['postfix']],
            ['100 000 Ft', Viewhelper_Project::getSalary($project6)['salary'] . Viewhelper_Project::getSalary($project6)['postfix']],
        ];
    }
}
