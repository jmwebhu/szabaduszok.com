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
