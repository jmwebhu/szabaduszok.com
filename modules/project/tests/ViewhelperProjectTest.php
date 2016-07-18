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
    
    public function testGetSalaryDataProvider()
    {
        // Oraber, nincs felso
        $project1               = new Model_Project();
        $project1->salary_type  = 1;
        $project1->salary_low   = 1000;
        
        // Oraber, also != felso
        $project2               = new Model_Project();
        $project2->salary_type  = 1;
        $project2->salary_low   = 1000;
        $project2->salary_high  = 1500;
        
        // Fix osszeg, nincs felso
        $project3               = new Model_Project();
        $project3->salary_type  = 2;
        $project3->salary_low   = 100000;
        
        // Fix osszeg, also != felso
        $project4               = new Model_Project();
        $project4->salary_type  = 2;
        $project4->salary_low   = 100000;
        $project4->salary_high   = 150000; 
        
        // Oraber, also == felso
        $project5               = new Model_Project();
        $project5->salary_type  = 1;
        $project5->salary_low   = 1000;
        $project5->salary_high  = 1000;
        
        // Fix osszeg, also == felso
        $project6               = new Model_Project();
        $project6->salary_type  = 2;
        $project6->salary_low   = 100000;
        $project6->salary_high  = 100000;
        
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
